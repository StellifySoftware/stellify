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
        {{ formatted }}
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
    </component>
</template>

<script>
    import moment from 'moment';
    export default {
        props: ['opts', 'settings'],
        data() {
            return {
                items: {},
                now: new Date(),
                date: {},
            }
        },
        computed: {
            formatted() {
                return moment(this.items.datetime ? this.items.datetime : this.now).format(this.items.format ? this.items.format : 'dddd Do MMMM YYYY');
            },
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
