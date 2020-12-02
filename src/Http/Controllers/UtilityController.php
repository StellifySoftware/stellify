<?php

namespace Stellisoft\Stellify\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Stellisoft\Stellify\Block;
use Stellisoft\Stellify\Body;
use Stellisoft\Stellify\Settings;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class UtilityController extends Controller
{
    protected $taxonomy;
    private $userid;
    private $returnObject;
    private $user;
    private $data;
    private $siteid;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->userid = Auth::id();
            $this->user = Auth::user();
            $this->data = [];
            $this->returnObject = [
                'content' => [],
                'body' => [],
                'blocks' => [],
                'bodyBlocks' => [],
                'target' => null
            ];
            if (!empty($this->user)) {
                $this->siteid = $this->user->site_id;
                if (!empty($this->user->data)) {
                    $this->user->data = json_decode($this->user->data);
                }
            }
            return $next($request);
        });
    }

    protected function guidv4() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        sleep(1);
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function storeBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'slug' => 'required'
        ));
        Block::updateOrCreate(
            ['slug' => $inputData['slug'], 'user_id' => $this->userid],
            ['data' => json_encode($request->input())]
        );
        return response()->json(['response' => 'Saved']);
    }

    public function insertBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'type' => 'required',
            'targetSlug' => 'required',
            'commitMessage' => 'required',
            'position' => 'required'
        ));
        $slug = $this->guidv4();
        $block = [
            'type' => $inputData['type'],
            'slug' => $slug
        ];
        $body = Body::where(['slug' => $inputData['targetSlug'], 'site_id' => $this->siteid])->first();
        if (!empty($body)) {
            $existingBlocks = json_decode($body->data, true);
            if (empty($existingBlocks['data'])) {
                $existingBlocks['data'] = [];
                array_push($existingBlocks['data'], $slug);
            } else {
                array_splice($existingBlocks['data'],  $inputData['position'], 0, $slug);
            }
            Body::updateOrCreate(
                ['slug' => $inputData['targetSlug'], 'site_id' => $this->siteid],
                ['data' => json_encode($existingBlocks)]
            );
        } else {
            $body = Body::updateOrCreate(
                ['slug' => $inputData['targetSlug'], 'site_id' => $this->siteid],
                ['data' => json_encode(['data' => [$slug]])]
            );
        }
        $savedBlock = Block::updateOrCreate(
            ['slug' => $slug],
            [
                'user_id' => $this->userid, 
                'slug' => $slug,
                'commit_message' => $inputData['commitMessage'],
                'data' => json_encode($block)
            ]
        );
        return $savedBlock;
    }

    public function createBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'slug' => 'required',
            'bodySlug' => 'required'
        ));
        $slug = $this->guidv4();
        $block = [
            'type' => "s-nowrapper",
            'slug' => $slug,
            'data' => [],
            'componentClasses' => []
        ];
        $body = Body::where(['slug' => $inputData['bodySlug']])->first();
        $targetBlock = Block::where(['slug' => $inputData['slug']])->first();
        if (!empty($body)) {
            $existingBlocks = json_decode($body->data, true);
            if (empty($existingBlocks['blocks'])) {
                $existingBlocks['blocks'] = [];
                array_push($existingBlocks['blocks'], $slug);
            } else {
                array_splice($existingBlocks['blocks'],  0, 0, $slug);
            }
            Body::updateOrCreate(
                ['slug' => $inputData['bodySlug']],
                ['data' => json_encode($existingBlocks)]
            );
        } else {
            $body = Body::updateOrCreate(
                ['slug' => $inputData['bodySlug']],
                ['data' => json_encode(['blocks' => [$slug]])]
            );
        }
        if (!empty($targetBlock)) {
            $existingTargetBlocks = json_decode($targetBlock->data, true);
            if (empty($existingTargetBlocks['data'])) {
                $existingTargetBlocks['data'] = [];
                array_push($existingTargetBlocks['data'], $slug);
            } else {
                array_splice($existingTargetBlocks['data'],  0, 0, $slug);
            }
            Block::updateOrCreate(
                ['slug' => $inputData['slug']],
                ['data' => json_encode($existingTargetBlocks)]
            );
        } 
        $savedBlock = Block::updateOrCreate(
            ['slug' => $slug],
            [
                'user_id' => $this->userid, 
                'slug' => $slug,
                'data' => json_encode($block)
            ]
        );
        return $savedBlock;
    }

    public function diffBlocks($target, $block) {
        $this->body1 = Body::where(['slug' => $target])->first();
        $this->body2 = Body::where(['slug' => $block])->first(); 
        $data1 = json_decode($this->body1->data);         
        $data2 = json_decode($this->body2->data);    
        $diff = array_diff($data1->blocks, $data2->blocks);        
        $out = array_values($diff);
        dd(json_encode($out, true));
    }

    public function duplicateBlock($target, $block) {
        $block = Block::where(['slug' => $block])->first(); 
        if (!empty($block->data)) {
            $blockData = json_decode($block->data);
            if (!empty($blockData)) {
                $newBlockSlug = $this->guidv4();
                $offset = array_search($block->slug, $this->returnObject['content'][$target]->data);
                array_splice($this->returnObject['content'][$target]->data, $offset, 1, $newBlockSlug);
                $this->returnObject['content'][$newBlockSlug] = $blockData;
                //insert the fresh duplicate target block
                if (empty($existingBlocks['data'])) {
                    $existingBlocks['data'] = [];
                } 
                //update the parent block
                Block::updateOrCreate(
                    ['slug' => $target],
                    [
                        'user_id' => $this->userid, 
                        'slug' => $target,
                        'data' => json_encode($this->returnObject['content'][$target])
                    ]
                );
                //create the new block
                Block::updateOrCreate(
                    ['slug' => $newBlockSlug],
                    [
                        'user_id' => $this->userid, 
                        'slug' => $newBlockSlug,
                        'data' => json_encode($blockData)
                    ]
                );
                //update the list of blocks to append to the body
                array_push($this->returnObject['bodyBlocks'], $newBlockSlug);
                if (!empty($blockData->data)) {
                    foreach($blockData->data as $slug) {
                        $this->duplicateBlock($newBlockSlug, $slug);
                    }
                }
            }
        }
        return;
    }

    public function insertDuplicateBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'parent' => 'required',
            'target' => 'required',
            'body' => 'required',
            'save' => ''
        ));
        $parent = Block::where(['slug' => $inputData['parent']])->first(); 
        $target = Block::where(['slug' => $inputData['target']])->first(); 
        $body = Body::where(['slug' => $inputData['body']])->first();
        if (!empty($body)) {
            $targetBody = json_decode($body->data);
            $targetData = json_decode($target->data);
            if (!empty($targetData->data)) {
                //store top level object
                $newTargetSlug = $this->guidv4();
                $targetData->slug = $newTargetSlug;
                $this->returnObject['content'][$newTargetSlug] = $targetData;
                $this->returnObject['block'] = $newTargetSlug;
                $this->returnObject['target'] = $parent->slug;
                //add it to the list of body blocks that need to be appended
                array_push($this->returnObject['bodyBlocks'], $newTargetSlug);
                foreach($targetData->data as $slug) {
                    $this->duplicateBlock($newTargetSlug, $slug);
                }
            }
            //Append the new reference to the parent
            if (!empty($parent->data)) {
                $existingParentBlocks = json_decode($body->data, true);
                if (empty($existingParentBlocks['data'])) {
                    $existingParentBlocks['data'] = [];
                }
                array_push($existingParentBlocks['blocks'], $newTargetSlug);
                Body::updateOrCreate(
                    ['slug' => $inputData['target']],
                    ['data' => json_encode($existingParentBlocks)]
                );
            }
            //Save all the new references to the body
            if (!empty($body->data)) {
                $existingBlocks = json_decode($body->data, true);
                if (empty($existingBlocks['blocks'])) {
                    $existingBlocks['blocks'] = [];
                }
                $existingBlocks['blocks'] = array_merge($existingBlocks['blocks'], $this->returnObject['bodyBlocks']);
                Body::updateOrCreate(
                    ['slug' => $inputData['target']],
                    ['data' => json_encode($existingBlocks)]
                );
            }
        }
        return $this->returnObject;
        //dd($this->returnObject);
    }

    public function insertSubBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'type' => 'required',
            'targetSlug' => 'required',
            'bodySlug' => 'required',
            'position' => 'required',
            'commitMessage' => 'required'
        ));
        $body = Body::where(['slug' => $inputData['bodySlug'], 'site_id' => $this->siteid])->first(); 
        if (!empty($body)) {
            $parentBlock = Block::where(['slug' => $inputData['targetSlug'], 'user_id' => $this->userid])->first(); 
            $slug = $this->guidv4();
            $block = [
                'type' => $inputData['type'],
                'slug' => $slug
            ];
            $parentBlockArray = json_decode($parentBlock->data, true);
            if (empty($parentBlockArray['data'])) {
                $parentBlockArray['data'] = [];
                array_push($parentBlockArray['data'], $slug);
            } else {
                array_splice($parentBlockArray['data'], $inputData['position'], 0, $slug);
            }
            Block::updateOrCreate(
                ['slug' => $inputData['targetSlug'], 'user_id' => $this->userid],
                ['data' => json_encode($parentBlockArray)]
            );
            $savedBlock = Block::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $this->userid, 
                    'slug' => $slug,
                    'data' => json_encode($block),
                    'commit_message' => $inputData['commitMessage']
                ]
            );
            $bodyData = json_decode($body->data, true);
            if (empty($bodyData['blocks'])) {
                $bodyData['blocks'] = [];
            }
            array_push($bodyData['blocks'], $slug);
            Body::updateOrCreate(
                ['slug' => $inputData['bodySlug'], 'site_id' => $this->siteid],
                ['data' => json_encode($bodyData)]
            );
            return $savedBlock;
        }
    }

    public function deleteBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'path' => 'required',
            'slug' => 'required'
        ));
        $body = Body::where('path', $inputData['path'])
            ->where('user_id', $this->userid)
            ->first();
        if (!empty($body->data)) {
            $bodyDataArray = json_decode($body->data, true);
            $pos = array_search($inputData['slug'], $bodyDataArray['data']);
            array_splice($bodyDataArray['data'], $pos, 1);
            $body->data = json_encode($bodyDataArray);
            $body->save();
            $block = Block::where('slug', $inputData['slug'])
                ->where('user_id', $this->userid)
                ->delete(); 
            return response()->json(['response' => 'Block deleted']);
        }
    }

    public function deleteSubBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'parentSlug' => 'required',
            'bodySlug' => 'required',
            'position' => 'required',
            'slug' => 'required'
        ));
        //remove the body reference
        $body = Body::where('slug', $inputData['bodySlug'])
            ->where('site_id', $this->siteid)
            ->first();
        $bodyData = json_decode($body->data, true);
        $bodyPos = array_search($inputData['slug'], $bodyData['blocks']);
        array_splice($bodyData['blocks'], $bodyPos, 1);
        $body->data = json_encode($bodyData);
        $body->save();
        //remove the parent reference
        $parentBlock = Block::where('slug', $inputData['parentSlug'])->first();
        $parentBlockArray = json_decode($parentBlock->data, true);
        if (!empty($parentBlockArray["data"])) {
            $pos = array_search($inputData['slug'], $parentBlockArray["data"]);
            array_splice($parentBlockArray["data"], $pos, 1);
            $parentBlock->data = json_encode($parentBlockArray);
            $parentBlock->save();
        }
        //remove the block itself
        //currently has to belong to user that created it
        $deletedBlock = Block::where('slug', $inputData['slug'])
            ->where('user_id', $this->userid)
            ->first(); 
        return response()->json(['response' => 'Successfully deleted']);
    }

    public function deleteAll(Request $request) {
        $inputData = $this->validate($request, array(
            'bodySlug' => 'required'
        ));
        $body = Body::where(['slug' => $inputData['bodySlug'], 'site_id' => $this->siteid])->first(); 
        if (!empty($body)) {
            $bodyData = json_decode($body->data, true);
            if (!empty($bodyData['data'])) {
                Block::whereIn('slug', $bodyData['data'])->delete();
            }
            if (!empty($bodyData['blocks'])) {
                Block::whereIn('slug', $bodyData['blocks'])->delete();
            }
            $body->data = '{}';
            $body->save();
            return response()->json(['response' => 'Deleted']);
        }
        return response()->json(['response' => 'There was a problem. Please contact us.']);
    }

    public function deleteAllBlocks(Request $request) {
        $inputData = $this->validate($request, array(
            'slug' => 'required',
            'path' => 'required',
        ));
        $parentBlock = Block::where('slug', $inputData['slug'])->first();
        $parentBlockArray = json_decode($parentBlock->data, true);
        foreach($parentBlockArray['data'] as $block) {
            $this->deleteBlockRecursive($block);
        }
        $parentBlockArray['data'] = [];
        $parentBlock->data = json_encode($parentBlockArray);
        $parentBlock->save();
    }

    public function move(Request $request) {
        $inputData = $this->validate($request, array(
            'slug' => 'required',
            'path' => 'required',
            'direction' => ''
        ));
        $body = Body::where('slug', $inputData['path'])
            ->where('user_id', $this->userid)
            ->first();
        $bodyDataArray = json_decode($body->data, true);
        $index = array_search($inputData['slug'], $bodyDataArray['data']);
        $block = array_splice($bodyDataArray['data'], $index, 1);
        if (!empty($inputData['direction'])) {
            array_splice($bodyDataArray['data'], $index + 1, 0, $block[0]);
        } else {
            array_splice($bodyDataArray['data'], $index - 1, 0, $block[0]);
        }
        $body->data = json_encode($bodyDataArray);
        $body->save();
        return response()->json(['response' => 'Saved']);
    }

    public function moveSubBlock(Request $request) {
        $inputData = $this->validate($request, array(
            'slug' => 'required',
            'parent' => 'required',
            'direction' => ''
        ));
        $parent = Block::where('slug', $inputData['parent'])
            ->where('user_id', $this->userid)
            ->first();
        $blockDataArray = json_decode($parent->data, true);
        $index = array_search($inputData['slug'], $blockDataArray['data']);
        $block = array_splice($blockDataArray['data'], $index, 1);
        if (!empty($inputData['direction'])) {
            array_splice($blockDataArray['data'], $index + 1, 0, $block[0]);
        } else {
            array_splice($blockDataArray['data'], $index - 1, 0, $block[0]);
        }
        $parent->data = json_encode($blockDataArray);
        $parent->save();
        return response()->json(['response' => 'Saved']);
    }
}
