<?php

namespace Stellisoft\Stellify\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Stellisoft\Stellify\Body;
use Stellisoft\Stellify\Block;
use Stellisoft\Stellify\Settings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\json_decode;

class PagesController extends Controller
{
    private $body;
    private $data;
    private $userid;
    private $user;
    private $siteid;
    private $live;
    private $root;
    private $path;
    private $settings;
    private $fonts;
    private $mode;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->root = $request->root();
            $this->path = $request->path();
            $this->url = $request->url();
            $this->userid = null;
            $this->user = null;
            $this->siteid = null;
            $this->live = false;
            $this->settings = [];
            $this->path = $request->path();
            $this->mode = $request->has('edit');
            $this->js = [];
            $this->app = 'app';
            $uriArray = explode('/', $this->path);
            $this->live = User::where('active_domain', $this->root)
                    ->orWhere('active_domain_secure', $this->root)
                    ->first();
            if (!empty($this->live)) {
                //Request from a live site
                $this->siteid = $this->siteOwner->site_id;
                $settings = Settings::select('data')
                    ->where('site_id', $this->siteid)
                    ->first();
                $this->settings = json_decode($settings->data, true);
                $this->settings['locked'] = true;
                $this->body = Body::where(['path' => $this->path, 'site_id' => $this->siteid])->first();
            } else {
                //Internal request
                $this->user = Auth::user();
                if (!empty($this->user)) {
                    $this->siteid = !empty($this->user) ? $this->user->site_id : 0;
                    $this->userid = Auth::id();
                }
                $this->body = Body::where(['path' => $this->path, 'site_id' => 0])->orWhere(['slug' => $this->path])->first();    
                if (!empty($this->body)) {
                    $this->siteid = $this->body->site_id;
                    $settings = Settings::select('data')
                        ->where('site_id', $this->siteid)
                        ->first();
                    if (!empty($settings)) {
                        $this->settings = json_decode($settings->data, true);
                    }
                    $this->settings['locked'] = true;
                    $this->settings['mode'] = false;
                    if (!empty($this->user) && $this->siteid == $this->user->site_id) {
                        $this->settings['locked'] = false;
                    }
                }
            }
            if (!empty($this->user) && !empty($this->user->data)) {
                $userData = json_decode($this->user->data);
                foreach($userData as $userDataKey => $userDataItem) {
                    $this->user[$userDataKey] = $userDataItem;
                }
            }
            if (!empty($this->body)) {
                if ($this->body->loggedin && empty($this->user)) {
                    return redirect('/login');
                }
                $data = json_decode($this->body->data);
                foreach($data as $key => $item) {
                    if ($key != 'data') {
                        $this->body[$key] = $item;
                    }
                }
                if (!empty($data->data)) {
                    $layouts = Block::whereIn('slug', $data->data)->get();
                    foreach($layouts as $layout) {
                        $this->data[$layout->slug] = json_decode($layout->data);
                        if (!empty($this->data[$layout->slug]->fetchFields)) {
                            $fetchedFields = $this->fetchFields($this->data[$layout->slug]->fetchFields);
                            if (!empty($fetchedFields)) {
                                $fetchedFieldsData = json_decode($fetchedFields->data);
                                foreach($fetchedFieldsData as $fetchedFieldKey => $fetchedFieldItem) {
                                    $this->data[$layout->slug]->{$fetchedFieldKey} = $fetchedFieldItem;
                                }
                            }
                        }
                    }
                }
                if (!empty($data->blocks)) {
                    $blocks = Block::whereIn('slug', $data->blocks)->get();
                    foreach($blocks as $block) {
                        $this->data[$block->slug] = json_decode($block->data);
                    }
                    if (!empty($this->site)) {
                        $siteBlocks = Block::whereIn('slug', $data->blocks)->where('site', $this->site)->get();
                        foreach($siteBlocks as $siteBlock) {
                            $this->data[$siteBlock->slug] = json_decode($siteBlock->data);
                        }
                    }
                }
                if (!empty($data->bodies)) {
                    $bodies = Body::whereIn('slug', $data->bodies)->get();
                    foreach($bodies as $body) {
                        $this->data[$body->slug] = $body;
                        $bodyData = json_decode($body->data);
                        foreach($bodyData as $bodyDataKey => $bodyDataItem) {
                            $this->data[$body->slug][$bodyDataKey] = $bodyDataItem;
                        }
                    }
                }
                if (!empty($this->settings['bodies'])) {
                    $globalBodies = Body::whereIn('slug', $this->settings['bodies'])->get();
                    foreach($globalBodies as $globalBody) {
                        $this->data[$globalBody->slug] = $globalBody;
                        $globalBodyData = json_decode($globalBody->data);
                        foreach($globalBodyData as $globalBodyDataKey => $globalBodyDataItem) {
                            $this->data[$globalBody->slug][$globalBodyDataKey] = $globalBodyDataItem;
                        }
                    }
                }
                if (!empty($this->settings['blocks'])) {
                    $globalBlocks = Block::whereIn('slug', $this->settings['blocks'])->get();
                    foreach($globalBlocks as $globalBlock) {
                        $this->data[$globalBlock->slug] = json_decode($globalBlock->data);
                    }
                }
                if (!empty($this->settings['fonts'])) {
                    foreach($this->settings['fonts'] as $font) {
                        $this->fonts[] = $font;
                    }
                }
                if (!empty($this->body['fonts'])) {
                    foreach($this->body['fonts'] as $font) {
                        $this->fonts[] = $font;
                    }
                }
            }
            return $next($request);
            
        });
    }

    public function index(Request $request) {
        return view('skeleton::page', [
            "editMode" => $this->mode ? 'edit' : '',
            "app" => !empty($this->app) ? $this->app : 'app',
            "fonts" => !empty($this->fonts) ? $this->fonts : null,
            "body" => !empty($this->body) ? $this->body : null,
            "content" => !empty($this->data) ? $this->data : null,
            "user" => !empty($this->user) ? $this->user : null,
            "settings" => $this->settings,
            "dev" => env('APP_DEBUG')
        ]);
    }
}
