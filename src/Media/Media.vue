<template>
    <component
        :style="[styles]"
        :is="computedTag"
        :controls="!items.disableControls"
        :playsinline="items.playsinline" 
        :autoplay="items.autoplay" 
        :preload="items.preload" 
        :muted="items.muted" 
        :loop="items.loop"
        :class="classes">
        <component
            v-for="(item, index) in items.data" 
            :key="index"
            :content="content"
            :record="record"
            :indicator="indicator"
            :settings="settings"
            :is="content[item].type"
            :opts="content[item]">
        </component>
        <p>{{ items.alt ? items.alt : 'Your browser does not support HTML5.' }}</p>
    </component>
</template>

<script>
    export default {
        props: {
            record: {
                type: String,
                default: null
            },
            indicator: {
                type: String,
                default: null
            },
            opts: {
                type: Object,
                default: function () { return {} }
            },
            body: {
                type: Object,
                default: function () { return {} }
            },
            settings: {
                type: Object,
                default: function () { return {} }
            },
            content: {
                type: [Object, Array],
                default: function () { return {} }
            }
        },
        data() {
            return {
                items: {}
            }
        },
        computed: {
            computedTag() {
                return this.items.tag ? this.items.tag : 'div'
            },
            classes() {
                if (this.settings[this.items.type] && this.settings[this.items.type].length > 0) {
                    return this.settings[this.items.type];
                }
                return this.items.classes;
            },
            styles() {
                return {
                    fontFamily: this.items.fontFamily,
                    minHeight: this.items.minHeight,
                    backgroundImage: this.items.backgroundImage ? 'url(' + this.items.backgroundImage + ')' : null
                };
            }
        },
        beforeMount: function () {
            if (this.opts != null) {
                this.items = this.opts;
            }
        }
    }
</script>
