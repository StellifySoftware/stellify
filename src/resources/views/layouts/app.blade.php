<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ !empty($body->meta_title) ? $body->meta_title : '' }}</title>
        <link rel='canonical' href="{{ url()->current() }}" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta name="keywords" content="{{ !empty($body->meta_keywords) ? $body->meta_keywords : '' }}">
        <meta name="description" content="{{ !empty($body->meta_description) ? $body->meta_description : '' }}">
        @if (!empty($editMode))
        <style>
            .editGrid {
                --osui_frame-header-height: 5.6rem;
                display: grid;
                grid-template-columns: 100%;
                grid-template-rows: auto 1fr auto auto;
                grid-template-areas:
                    "header"
                    "main"
                    "children"
                    "sidebar"
                    "footer";
                width: 100vw;
                height: 100vh;
                background-color: #f4f6f8;
                overflow: hidden;
            }
            @media (min-width: 41.6875em) {
                .editGrid {
                    transition: grid-template-columns .2s cubic-bezier(.64,0,.35,1);
                    grid-template-columns: 20rem 1fr;
                    grid-template-areas:
                        "header header"
                        "sidebar main"
                        "sidebar children"
                        "footer footer";
                }
            }
            @media (min-width: 41.6875em) {
                .editSidebar {
                    grid-area: sidebar;
                    z-index: 10;
                    position: relative;
                    width: 20rem;
                    overflow: hidden;
                    transition: opacity .2s cubic-bezier(.64,0,.35,1);
                }
            }
            .editHeader {
                grid-area: header;
                z-index: 50;
                width: 100%;
            }
            .editMain {
                grid-area: main;
                z-index: 40;
                position: relative;
                display: flex;
            }
            .QEYD4 {
                display: flex;
                flex-direction: column;
                flex: 1 1 auto;
                justify-content: center;
                width: 100%;
            }
            .checked {
                background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' stroke='black' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
            }
        </style>
        @endif
        @component('head', ['settings' => $settings, 'fonts' => $fonts])
        
        @endcomponent
        @if (empty($dev) && !empty($settings['google-analytics-tracking-code']))
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={!! $settings['google-analytics-tracking-code'] !!})"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{!! $settings['google-analytics-tracking-code'] !!}');
            </script>
        @endif
        @if (!empty($mode) && $mode == 'edit')
        <script defer src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        @endif
    </head>
    <body>  
        @if (!empty($body))
        <div id="app">
            @if (!empty($editMode) && $mode == 'edit')
            <div class="block rounded-md bg-yellow-50 p-4" :class="{'sm:hidden': true}">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        </div>
                        <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Attention needed
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>
                                Yakety only works on a tablet or desktop device.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invisible" :class="{editGrid: !preview, 'sm:visible': true}">
                <header class="editHeader">
                    <nav class="bg-white shadow">
                        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                            <div class="relative flex justify-between h-16 flex items-center md:space-x-10">
                                <div class="lg:w-0 lg:flex-1">
                                    <a :href="body.path" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" aria-label="Main menu" aria-expanded="false">
                                        <svg viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" class="h-5 w-5 text-gray-400" focusable="false" aria-hidden="true"><path d="M1 0a1 1 0 00-1 1v3a1 1 0 002 0V2h16v16H2v-2a1 1 0 10-2 0v3a1 1 0 001 1h18a1 1 0 001-1V1a1 1 0 00-1-1H1z"></path><path d="M6.414 9H14a1 1 0 110 2H6.414l3.293 3.293a.999.999 0 11-1.414 1.414l-5-5a.999.999 0 010-1.414l5-5a.999.999 0 111.414 1.414L6.414 9z"></path></svg>
                                    </a>
                                </div>
                                <button @click="preview = !preview" type="button" style="width: 2.75rem;" aria-pressed="false" :class="preview ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="sr-only">Toggle preview mode</span>
                                    <span :class="preview ? 'translate-x-5' : 'translate-x-0'" class="translate-x-0 relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                                        <span :class="preview ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'" class="opacity-100 ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                            <svg v-show="!preview" class="h-3 w-3 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </span>
                                        <span :class="preview ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'" class="opacity-0 ease-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                            <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </nav>
                </header>
                @if (!empty($deviceMode))
                <main style="padding: 1.6rem 1.6rem 1.6rem 1.6rem;grid-column: 1 / 3;overflow-x-scroll">
                    <div :style="editWrapper">
                        <iframe style="width:100%;height:100vw;" src="{{ 'http://localhost:8080/' . Request::path() . '?noiframe=true' }}"></iframe>
                    </div>
                </main>
                @else
                <aside v-if="currentSelection && !preview" class="editSidebar">
                    <div class="relative w-full h-full">
                        <div class="block absolute top-0 left-0 z-20 w-full h-full">
                            <div class="flex flex-col h-0 flex-1 px-4 pt-2">
                                <div class="flex-1 flex flex-col pt-5 pb-4">
                                    <nav class="flex-1 px-2 space-y-1">
                                        <draggable v-if="content[currentSelection]" v-model="content[currentSelection].data" @end="onDragEnd">
                                            <transition-group>
                                                <div v-for="(item, index) in content[currentSelection].data" :index="index" :key="item" class="col-span-1 flex shadow-sm">
                                                    <div class="flex-1 flex items-center justify-between border-t border-r border-gray-200 bg-white truncate">
                                                        <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                            <button @click.stop="selectBlock(item)" class="text-gray-900 font-medium hover:text-gray-600 transition ease-in-out duration-150 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                               Select <@{{ content[item].tag ? content[item].tag : 'div' }}>
                                                            </button>
                                                        </div>
                                                        <div class="flex-shrink-0 pr-2">
                                                            <button @click.stop="removeChildBlock(item)" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="handle cursor-move flex-shrink-0 pr-2">
                                                            <button class="w-8 h-8 cursor-move inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                <svg class="w-5 h-5" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </transition-group>
                                        </draggable>
                                        <div class="root">
                                            <ul class="grid grid-cols-1">
                                                <li @click.prevent="clearSelection" class="col-span-1 flex shadow-sm rounded-b-md mt-3 cursor-pointer">
                                                    <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-md truncate">
                                                        <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                            <a class="text-blue-900 font-medium hover:text-blue-600 transition ease-in-out duration-150">Back to top</a>
                                                        </div>
                                                        <div class="flex-shrink-0 pr-2">
                                                            <a class="w-8 h-8 inline-flex items-center justify-center text-blue-400 rounded-full bg-transparent hover:text-blue-500 focus:outline-none focus:text-blue-500 focus:bg-blue-100 transition ease-in-out duration-150">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li @click.prevent="displayEditor" class="col-span-1 flex shadow-sm rounded-b-md mt-3 cursor-pointer">
                                                    <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-md truncate">
                                                        <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                            <a class="text-blue-900 font-medium hover:text-blue-600 transition ease-in-out duration-150">Edit <@{{ content[currentSelection].tag }}></a>
                                                        </div>
                                                        <div class="flex-shrink-0 pr-2">
                                                            <a class="w-8 h-8 inline-flex items-center justify-center text-blue-400 rounded-full bg-transparent hover:text-blue-500 focus:outline-none focus:text-blue-500 focus:bg-blue-100 transition ease-in-out duration-150">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="cursor-pointer col-span-1 flex shadow-sm rounded-b-md mt-3">
                                                    <div @click.stop="insertChildBlock('s-wrapper')" class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-md truncate">
                                                        <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                            <a href="#" class="text-blue-900 font-medium hover:text-blue-600 transition ease-in-out duration-150">Insert element</a>
                                                            <p class="text-gray-500"></p>
                                                        </div>
                                                        <div class="flex-shrink-0 pr-2">
                                                        <button class="w-8 h-8 inline-flex items-center justify-center text-blue-400 rounded-full bg-transparent hover:text-blue-500 focus:outline-none focus:text-blue-500 focus:bg-blue-100 transition ease-in-out duration-150">
                                                            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" focusable="false" aria-hidden="true"><path d="M3 3h1V1H2.5A1.5 1.5 0 0 0 1 2.5V4h2V3zm3 0h3V1H6v2zm5 0h3V1h-3v2zM9 19H6v-2h3v2zm2 0h3v-2h-3v2zm6-15V3h-1V1h1.5A1.5 1.5 0 0 1 19 2.5V4h-2zM3 17v-1H1v1.5A1.5 1.5 0 0 0 2.5 19H4v-2H3zm13 0h1v-1h2v1.5a1.5 1.5 0 0 1-1.5 1.5H16v-2zM10 6a1 1 0 0 1 1 1v2h2a1 1 0 1 1 0 2h-2v2a1 1 0 1 1-2 0v-2H7a1 1 0 1 1 0-2h2V7a1 1 0 0 1 1-1zM1 9V6h2v3H1zm0 2v3h2v-3H1zm16-2V6h2v3h-2zm0 2v3h2v-3h-2z"></path></svg>    
                                                        </button>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                <aside v-else-if="!preview" class="editSidebar">
                    <div class="relative w-full h-full">
                        <div class="block absolute top-0 left-0 z-20 w-full h-full">
                            <div class="flex flex-col h-0 flex-1 px-4 pt-2">
                                <div class="flex-1 flex flex-col pt-5 pb-4">
                                    <nav class="flex-1 px-2 space-y-1">
                                        <div class="root">
                                            <div class="col-span-1 flex">
                                                <div class="flex-1 flex items-center justify-between">
                                                    <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                        <button class="text-gray-900 font-medium hover:text-gray-600 transition ease-in-out duration-150 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                            Header
                                                        </button>
                                                    </div>
                                                    <div class="flex-shrink-0 pr-2">
                                                        <button class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                                                <path d="M6,2H18A2,2 0 0,1 20,4V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V4A2,2 0 0,1 6,2M6,4V8H18V4H6Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <draggable v-model="settings.header">
                                                <transition-group>
                                                    <div v-for="(item, index) in settings.header" :index="index" :key="item" class="col-span-1 flex shadow-sm">
                                                        <div class="flex-1 flex items-center justify-between border-t border-r border-gray-200 bg-white truncate">
                                                            <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                                <button @click.stop="selectBlock(item)" class="text-gray-900 font-medium hover:text-gray-600 transition ease-in-out duration-150 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                    Select <@{{ content[item].tag ? content[item].tag : 'div' }}>
                                                                </button>
                                                            </div>
                                                            <div class="flex-shrink-0 pr-2">
                                                                <button @click.stop="removeBlock(item, true, 'header')" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="handle cursor-move flex-shrink-0 pr-2">
                                                                <button class="w-8 h-8 cursor-move inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                    <svg class="w-5 h-5" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </transition-group>
                                            </draggable>
                                        </div>
                                        <div class="root">
                                            <div class="col-span-1 flex">
                                                <div class="flex-1 flex items-center justify-between">
                                                    <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                        <button class="text-gray-900 font-medium hover:text-gray-600 transition ease-in-out duration-150 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                            Main
                                                        </button>
                                                    </div>
                                                    <div class="flex-shrink-0 pr-2">
                                                        <button class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                                                <path d="M6,2H18A2,2 0 0,1 20,4V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V4A2,2 0 0,1 6,2M6,8V16H18V8H6Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <draggable v-model="blocks">
                                                <transition-group>
                                                    <div v-for="(item, index) in blocks" :index="index" :key="item" class="col-span-1 flex shadow-sm">
                                                        <div class="flex-1 flex items-center justify-between border-t border-r border-gray-200 bg-white truncate">
                                                            <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                                <button @click.stop="selectBlock(item)" class="text-gray-900 font-medium hover:text-gray-600 transition ease-in-out duration-150 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                    Select <@{{ content[item].tag ? content[item].tag : 'div' }}>
                                                                </button>
                                                            </div>
                                                            <div class="flex-shrink-0 pr-2">
                                                                <button @click.stop="removeBlock(item)" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="handle cursor-move flex-shrink-0 pr-2">
                                                                <button class="w-8 h-8 cursor-move inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                    <svg class="w-5 h-5" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </transition-group>
                                            </draggable>
                                        </div>
                                        <div class="root">
                                            <div class="col-span-1 flex">
                                                <div class="flex-1 flex items-center justify-between">
                                                    <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                        <button class="text-gray-900 font-medium hover:text-gray-600 transition ease-in-out duration-150 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                        Footer
                                                        </button>
                                                    </div>
                                                    <div class="flex-shrink-0 pr-2">
                                                        <button class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                                                <path d="M6,2H18A2,2 0 0,1 20,4V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V4A2,2 0 0,1 6,2M6,16V20H18V16H6Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="grid grid-cols-1">
                                                <draggable v-model="settings.footer">
                                                    <transition-group>
                                                        <div v-for="(item, index) in settings.footer" :index="index" :key="item" class="col-span-1 flex shadow-sm">
                                                            <div class="flex-1 flex items-center justify-between border-t border-r border-gray-200 bg-white truncate">
                                                                <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                                    <button @click.stop="selectBlock(item)" class="text-gray-900 font-medium hover:text-gray-600 transition ease-in-out duration-150 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                        Select <@{{ content[item].tag ? content[item].tag : 'div' }}>
                                                                    </button>
                                                                </div>
                                                                <div class="flex-shrink-0 pr-2">
                                                                    <button @click.stop="removeBlock(item, true, 'footer')" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="handle cursor-move flex-shrink-0 pr-2">
                                                                    <button class="w-8 h-8 cursor-move inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                                        <svg class="w-5 h-5" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path></svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </transition-group>
                                                </draggable>
                                                <li class="cursor-pointer col-span-1 flex shadow-sm rounded-b-md mt-3">
                                                    <div @click.stop="insertBlock('s-wrapper')" class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-md truncate">
                                                        <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                            <a href="#" class="text-blue-900 font-medium hover:text-blue-600 transition ease-in-out duration-150">Insert element</a>
                                                            <p class="text-gray-500"></p>
                                                        </div>
                                                        <div class="flex-shrink-0 pr-2">
                                                        <button class="w-8 h-8 inline-flex items-center justify-center text-blue-400 rounded-full bg-transparent hover:text-blue-500 focus:outline-none focus:text-blue-500 focus:bg-blue-100 transition ease-in-out duration-150">
                                                            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" focusable="false" aria-hidden="true"><path d="M3 3h1V1H2.5A1.5 1.5 0 0 0 1 2.5V4h2V3zm3 0h3V1H6v2zm5 0h3V1h-3v2zM9 19H6v-2h3v2zm2 0h3v-2h-3v2zm6-15V3h-1V1h1.5A1.5 1.5 0 0 1 19 2.5V4h-2zM3 17v-1H1v1.5A1.5 1.5 0 0 0 2.5 19H4v-2H3zm13 0h1v-1h2v1.5a1.5 1.5 0 0 1-1.5 1.5H16v-2zM10 6a1 1 0 0 1 1 1v2h2a1 1 0 1 1 0 2h-2v2a1 1 0 1 1-2 0v-2H7a1 1 0 1 1 0-2h2V7a1 1 0 0 1 1-1zM1 9V6h2v3H1zm0 2v3h2v-3H1zm16-2V6h2v3h-2zm0 2v3h2v-3h-2z"></path></svg>    
                                                        </button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="cursor-pointer col-span-1 flex shadow-sm rounded-b-md mt-3">
                                                    <div @click.stop="clearAll" class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-md truncate">
                                                        <div class="flex-1 px-4 py-2 text-sm leading-5 truncate">
                                                            <a href="#" class="text-blue-900 font-medium hover:text-blue-600 transition ease-in-out duration-150">Clear all</a>
                                                            <p class="text-gray-500"></p>
                                                        </div>
                                                        <div class="flex-shrink-0 pr-2">
                                                        <button class="w-8 h-8 inline-flex items-center justify-center text-blue-400 rounded-full bg-transparent hover:text-blue-500 focus:outline-none focus:text-blue-500 focus:bg-blue-100 transition ease-in-out duration-150">
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                <main v-if="currentSelection && !preview"  class="overflow-x-scroll" style="padding: 1.6rem 1.6rem 1.6rem 0;">
                    <section v-if="content[currentSelection].type">
                        <s-component
                            :is="content[currentSelection].type" 
                            :opts="content[currentSelection]"
                            :settings="settings"
                            :selection="currentSelection"
                            :content="content">
                        </s-component>
                    </section>
                </main>
                <main v-else class="overflow-x-scroll" :style="!preview ? 'padding: 1.6rem 1.6rem 1.6rem 0': ''">
                    <s-{{$app}} :class="{'w-full': !preview}" :settings="settings" :selection="currentSelection" :body="body" :blocks="blocks" :content="content" :errors="errors" :user="user" :users="users"></s-{{$app}}>
                </main>
                @endif
            </div>
            <div v-if="currentSelection && editor" class="fixed z-50 inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <section class="absolute right-0 max-w-full flex" :class="editorPosition == 'bottom' ? 'bottom-0 left-0' : 'pl-10 sm:pl-16 inset-y-0'" aria-labelledby="slide-over-heading">
                        <transition
                            enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
                            enter-class="translate-x-full"
                            enter-to-class="translate-x-0"
                            leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-class="otranslate-x-0"
                            leave-to-class="translate-x-full">
                            <div v-show="currentSelection" class="w-screen" :class="editorPosition == 'bottom' ? '' : 'max-w-2xl'" :style="editorPosition == 'bottom' ? 'height: 50vh' : ''">
                                <div class="h-full flex flex-col py-6 bg-white shadow-xl overflow-y-scroll">
                                    <div class="px-4 sm:px-6">
                                        <div class="flex items-start justify-between">
                                            <h2 id="slide-over-heading" class="text-lg font-medium text-gray-900">
                                                Editor
                                            </h2>
                                            <div class="ml-3 h-7 flex items-center">
                                                <button v-show="editorPosition == 'bottom'" @click="toggleEditorPosition" class="mr-3 bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <span class="sr-only">Editor align bottom</span>
                                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                                        <path fill="currentColor" d="M6,2H18A2,2 0 0,1 20,4V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V4A2,2 0 0,1 6,2M14,8V16H18V8H14Z" />
                                                    </svg>
                                                </button>
                                                <button v-show="editorPosition == 'right'" @click="toggleEditorPosition" class="mr-3 bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <span class="sr-only">Editior align top</span>
                                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                                        <path fill="currentColor" d="M6,2H18A2,2 0 0,1 20,4V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V4A2,2 0 0,1 6,2M6,16V20H18V16H6Z" />
                                                    </svg>
                                                </button>
                                                <button @click="closeEditor" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <span class="sr-only">Close panel</span>
                                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 relative flex-1 px-4 sm:px-6">
                                        <s-taginput
                                            v-model="content[currentSelection].classes"
                                            icon="label"
                                            :allow-new="true"
                                            :on-paste-separators="[' ']"
                                            closable
                                            attached
                                            placeholder="Add a new class...">
                                            <template slot-scope="props">
                                                @{{props.option}}
                                            </template>
                                        </s-taginput>
                                        <s-responsive :settings="settings" class="border-t border-gray-200"></s-responsive>
                                        <div class="border-t border-gray-200 px-4 py-5 sm:p-0 overflow-y-scroll" style="height: 30rem;">
                                            <div @click="open = 'element'" class="bg-white px-4 py-5 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Element
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'element'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Select Tag
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <select id="type" v-model="content[currentSelection].tag" class="form-select mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                        <option value="div">Divider</option>
                                                                        <option value="h1">Heading 1</option>
                                                                        <option value="p">Paragraph</option>
                                                                        <option value="img">Image</option>
                                                                        <option value="button">Button</option>
                                                                        <option value="input">Input</option>
                                                                        <option value="textarea">Text area</option>
                                                                        <option value="a">Link/ Anchor</option>
                                                                        <option value="header">Header</option>
                                                                        <option value="footer">Footer</option>
                                                                        <option value="main">Main</option>
                                                                        <option value="aside">Aside</option>
                                                                    </select>
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Manually input tag
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                            <div class="mt-1">
                                                                <input v-model.lazy="content[currentSelection].tag" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add text">
                                                            </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl v-if="content[currentSelection].tag == 'input'" class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Select Type
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <select id="type" v-model="content[currentSelection].subtype" class="form-select mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                        <option value="email">Email</option>
                                                                        <option value="color">Colour</option>
                                                                        <option value="number">Number</option>
                                                                        <option value="date">Date</option>
                                                                        <option value="week">Week</option>
                                                                        <option value="month">Month</option>
                                                                        <option value="file">File</option>
                                                                        <option value="password">Password</option>
                                                                        <option value="tel">Telephone</option>
                                                                        <option value="url">URL</option>
                                                                    </select>
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Title
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                            <div class="mt-1">
                                                                <input v-model="content[currentSelection].title" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add text">
                                                            </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl v-show="content[currentSelection].tag == 'input'" class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Name
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                            <div class="mt-1">
                                                                <input v-model="content[currentSelection].name" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add text">
                                                            </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl v-show="content[currentSelection].tag == 'a'" class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Link/ Href
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                            <div class="mt-1">
                                                                <input v-model="content[currentSelection].href" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add text">
                                                            </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl v-if="content[currentSelection].tag == 'a'" class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                                Target
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <select id="type" v-model="content[currentSelection].target" class="form-select mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                        <option value="_blank">Blank</option>
                                                                        <option value="_self">Self</option>
                                                                        <option value="_parent">Parent</option>
                                                                        <option value="_top">Top</option>
                                                                    </select>
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'text'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Text
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'text'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Text
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                            <div class="mt-1">
                                                                <input v-model="content[currentSelection].text" type="text" id="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add text">
                                                            </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div v-if="content[currentSelection].tag == 'img'" @click="open = 'image'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Image
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="content[currentSelection].tag == 'img' && open == 'image'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Upload Image
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <input
                                                                    class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                    ref="input"
                                                                    type="file"
                                                                    @change="onFileChange"
                                                                    value="Upload">
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Alt Text
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                            <div class="mt-1">
                                                                <input v-model="content[currentSelection].alt" type="text" id="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add text">
                                                            </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'layout'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Layout
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'layout'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Container
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <input type="checkbox" v-model="content[currentSelection].classes" name="color-chooser" id="container" value="container">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Display
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.display" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + item.class" class="block w-8 h-8 rounded-full" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + item.class)}]">@{{ item.name }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.display" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + item.class" :value="settings.prefix + item.class">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Position
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.position" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + item.class" class="block w-8 h-8 rounded-full" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + item.class)}]">@{{ item.name }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.position" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + item.class" :value="settings.prefix + item.class">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Z-Index
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in zIndex" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'z-' + item" style="line-height: 0;" class="border border-transparent h-16 w-16 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'z-' + item)}]">@{{ item }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in zIndex" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'z-' + item" :value="settings.prefix + 'z-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'grid'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Grid
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'grid'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Grid Columns
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in 12" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'grid-cols-' + index" style="line-height: 0;" class="border border-transparent h-10 w-10 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'grid-cols-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in 12" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'grid-cols-' + index" :value="settings.prefix + 'grid-cols-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Grid Rows
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in 6" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'grid-rows-' + index" style="line-height: 0;" class="border border-transparent h-10 w-10 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'grid-rows-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in 6" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'grid-rows-' + index" :value="settings.prefix + 'grid-rows-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Grid Gap
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in [0,1,2,3,4,5,6,7,8,9,10,11,12,14,16,20,24,28,32]" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'gap-' + index" style="line-height: 0;" class="border border-transparent h-10 w-10 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'gap-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in [0,1,2,3,4,5,6,7,8,9,10,11,12,14,16,20,24,28,32]" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'gap-' + index" :value="settings.prefix + 'gap-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'spacing'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Spacing
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'spacing'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Select Side
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <select id="type" v-model="side" class="form-select mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                        <option value="">All sides</option>
                                                                        <option value="x">Horizontal</option>
                                                                        <option value="y">Veritical</option>
                                                                        <option value="t">Top</option>
                                                                        <option value="r">Right</option>
                                                                        <option value="b">Bottom</option>
                                                                        <option value="l">Left</option>
                                                                    </select>
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Padding
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in paddingSizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'p' + side + '-' + index" style="line-height: 0;" class="border border-transparent h-12 w-12 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'p' + side + '-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in paddingSizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'p' + side + '-' + index" :value="settings.prefix + 'p' + side + '-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Margin
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in marginSizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + sign + 'm' + side + '-' + index" style="line-height: 0;" class="border border-transparent h-12 w-12 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + sign + 'm' + side + '-' + index)}]">@{{ sign + index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in marginSizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + sign + 'm' + side + '-' + index" :value="settings.prefix + sign + 'm' + side + '-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <button @click="sign == '' ? sign = '-' : sign = ''" type="button" style="width: 2.75rem;" aria-pressed="false" :class="sign == '' ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                    <span class="sr-only">Margin sign</span>
                                                                    <span :class="sign == '' ? 'translate-x-5' : 'translate-x-0'" class="translate-x-0 relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                                                                        <span :class="sign == '' ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'" class="opacity-100 ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                                                            <svg class="h-3 w-3 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                                                                            </svg>
                                                                        </span>
                                                                        <span :class="sign == '' ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'" class="opacity-0 ease-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                                                            <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                            </svg>
                                                                        </span>
                                                                    </span>
                                                                </button>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'sizing'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Sizing
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'sizing'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Width/ Height
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <select id="type" v-model="widthHeight" class="form-select mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                        <option value="w">Width</option>
                                                                        <option value="h">Height</option>
                                                                    </select>
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in sizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + widthHeight + '-' + index" style="line-height: 0;" class="border border-transparent h-16 w-16 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + widthHeight + '-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in sizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + widthHeight + '-' + index" :value="settings.prefix + widthHeight + '-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                                Max Widths
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in maxWidthSizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'max-w-' + index" style="line-height: 0;" class="border border-transparent h-32 w-32 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'max-w-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in maxWidthSizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'max-w-' + index" :value="settings.prefix + 'max-w-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                                Min Widths
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in minWidthSizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'min-w-' + index" style="line-height: 0;" class="border border-transparent h-16 w-16 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'min-w-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in minWidthSizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'min-w-' + index" :value="settings.prefix + 'min-w-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                                Max Height
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in maxHeightSizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'max-h-' + index" style="line-height: 0;" class="border border-transparent h-32 w-32 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'max-h-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in maxHeightSizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'max-h-' + index" :value="settings.prefix + 'max-h-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                                Min Height
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="index in minHeightSizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'min-h-' + index" style="line-height: 0;" class="border border-transparent h-16 w-16 pt-0.5 bg-green-400 flex items-center justify-center rounded-full text-lg leading-10 font-display font-bold text-white hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'min-h-' + index)}]">@{{ index }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="index in minHeightSizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'min-h-' + index" :value="settings.prefix + 'min-h-' + index">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'typography'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Typography
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'typography'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Font Family
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <select id="type" v-model="content[currentSelection].fontFamily" @change="fontSelection" class="form-select mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                                        <option value="ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'">Sans</option>
                                                                        <option value="ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif">Serif</option>
                                                                        <option value="ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace">Mono</option>
                                                                        <option v-for="font in families" :value="font.name">@{{font.name}}</option>
                                                                    </select>
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Font Size
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.fontSizes" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'text-' + item" style="line-height: 0;" class="align-middle block w-8 h-8" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'text-' + item)}, 'text-' + item]">a</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.fontSizes" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'text-' + item" :value="settings.prefix + 'text-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Text Colour
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.colours" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'text-' + item" class="border border-transparent shadow-md block w-8 h-8 rounded-full hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'text-' + item)}, 'bg-' + item]"></label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.colours" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'text-' + item" :value="settings.prefix + 'text-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Font Weight
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.weights" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'font-' + item" class="block w-8 h-8 rounded-full" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'font-' + item)}, 'font-' + item]">a</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.weights" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'font-' + item" :value="settings.prefix + 'font-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Text Style
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li class="flex-1 mt-2">
                                                                            <label for="italic" style="line-height: 0;" class="block w-8 h-8 italic font-serif text-xl" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes('italic')}]">italic</label>
                                                                            <label for="uppercase" style="line-height: 0;" class="block w-8 h-8 uppercase text-xl" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes('uppercase')}]">uppercase</label>
                                                                            <label for="lowercase" style="line-height: 0;" class="block w-8 h-8 lowercase text-xl" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes('lowercase')}]">lowercase</label>
                                                                            <label for="capitalize" style="line-height: 0;" class="block w-8 h-8 capitalize text-xl" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes('capitalize')}]">capitalize</label>
                                                                            <label for="underline" style="line-height: 0;" class="block w-8 h-8 underline text-xl" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes('underline')}]">underline</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" id="italic" value="italic">
                                                                    <input type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" id="uppercase" value="uppercase">
                                                                    <input type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" id="lowercase" value="lowercase">
                                                                    <input type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" id="capitalize" value="capitalize">
                                                                    <input type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" id="underline" value="underline">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Letter Spacing
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul>
                                                                        <li v-for="(item, index) in tracking" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'tracking-' + item" class="block" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'tracking-' + item)}, 'tracking-' + item]">The quick brown fox jumped over the lazy dog.</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in tracking" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'tracking-' + item" :value="settings.prefix + 'tracking-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Line height
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul>
                                                                        <li v-for="(item, index) in lineHeights" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'leading-' + item" class="block" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'leading-' + item)}, 'leading-' + item]">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, quia temporibus eveniet a libero incidunt suscipit laborum, rerum accusantium modi quidem, ipsam illum quis sed voluptatum quae eum fugit earum.</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in lineHeights" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'leading-' + item" :value="settings.prefix + 'leading-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'backgrounds'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Backgrounds
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'backgrounds'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Background Colour
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.colours" :key="index" class="mt-2">
                                                                            <label :for="settings.prefix + 'bg-' + item" class="border border-transparent shadow-md block w-8 h-8 rounded-full rounded-full hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'bg-' + item)}, 'bg-' + item]"></label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.colours" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'bg-' + item" :value="settings.prefix + 'bg-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Apply gradient to text
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="h-5 flex items-center">
                                                                    <input id="gradient" v-model="content[currentSelection].classes" type="checkbox" value="bg-clip-text text-transparent" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Linear Gradient
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.directions" :key="index" class="mt-2">
                                                                            <label :for="settings.prefix + 'bg-gradient-to-' + item" class="border border-transparent shadow-md block w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 rounded-full hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'bg-gradient-to-' + item)}, 'bg-gradient-to-' + item]"></label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.directions" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'bg-gradient-to-' + item" :value="settings.prefix + 'bg-gradient-to-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Gradient From Colour
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.colours" :key="index" class="mt-2">
                                                                            <label :for="settings.prefix + 'from-' + item" class="border border-transparent shadow-md bg-gradient-to-r block w-8 h-8 rounded-full hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'from-' + item)}, 'from-' + item]"></label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.colours" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'from-' + item" :value="settings.prefix + 'from-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Gradient Via Colour
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.colours" :key="index" class="mt-2">
                                                                            <label :for="settings.prefix + 'via-' + item" class="border border-transparent shadow-md bg-gradient-to-r from-white to-white block w-8 h-8 rounded-full hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'via-' + item)}, 'via-' + item]"></label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.colours" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'via-' + item" :value="settings.prefix + 'via-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Gradient To Colour
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.colours" :key="index" class="mt-2">
                                                                            <label :for="settings.prefix + 'to-' + item" class="border border-transparent shadow-md bg-gradient-to-r from-white block w-8 h-8 rounded-full hover:border-gray-300" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'to-' + item)}, 'to-' + item]"></label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.colours" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'to-' + item" :value="settings.prefix + 'to-' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                            <div @click="open = 'effects'" class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                                    <div class="ml-4 mt-2">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Effects
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <transition
                                                enter-active-class="transition ease-out duration-200"
                                                enter-class="opacity-0 translate-y-1"
                                                enter-to-class="opacity-100 translate-y-0"
                                                leave-active-class="transition ease-in duration-150"
                                                leave-class="opacity-100 translate-y-0"
                                                leave-to-class="opacity-0 translate-y-1">
                                                <div v-show="open == 'effects'">
                                                    <dl class="sm:divide-y sm:divide-gray-200">
                                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                            <dt class="text-sm font-medium text-gray-500">
                                                            Box Shadow
                                                            </dt>
                                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                                <div class="mt-4 sm:mt-0 sm:col-span-2">
                                                                    <ul class="color-swatches flex flex-wrap -space-x-1">
                                                                        <li v-for="(item, index) in settings.shadow" :key="index" class="flex-1 mt-2">
                                                                            <label :for="settings.prefix + 'shadow' + item" class="h-8 w-8 font-medium bg-white rounded-md flex items-center justify-center" :class="[{checked: content[currentSelection].classes && content[currentSelection].classes.includes(settings.prefix + 'shadow' + item)}, 'shadow' + item]">@{{ item == '-none' ? 'None': '' }}</label>
                                                                        </li>
                                                                    </ul>
                                                                    <input v-for="(item, index) in settings.shadow" :key="index" type="checkbox" v-model="content[currentSelection].classes" class="hidden" name="color-chooser" :id="settings.prefix + 'shadow' + item" :value="settings.prefix + 'shadow' + item">
                                                                </div>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </transition>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </section>
                </div>
            </div>
            @else
            <div id="content">
                @component('header')

                @endcomponent
                <s-{{$app}} :settings="settings" :body="body" :blocks="blocks" :content="content" :errors="errors" :user="user" :users="users"></s-{{$app}}>
            </div>
            @yield('content')
            @component('footer')

            @endcomponent
            @endif
        </div>
        <script defer type="text/javascript" src="/js/app{{$body->type}}{{$editMode}}.js"></script>
        <script>
            window.App = {!! json_encode([
                'content' => !empty($content) ? $content : [],
                'settings' => !empty($settings) ? $settings : [],
                'body' => !empty($body) ? $body : [],
                'user' => !empty($user) ? $user : [],
                'products' => !empty($products) ? $products : [],
                'stripepublic' => !empty($stripePublicKey) ? $stripePublicKey : '',
                'errors' => !empty($errors->all()) ? $errors->all() : [],
                'googleAuthRedirect' => !empty($settings['googleOneTap']) && empty($user) && !empty($googleauthredirect) ? $googleauthredirect : null
            ]) !!};
        </script>
        @else
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div>
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Congratulations!
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm leading-5 text-gray-500">
                                You've succesfully installed Stellify.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6">
                        <span class="flex w-full rounded-md shadow-sm">
                            <a href="/?edit" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Edit page
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </body>
</html>
