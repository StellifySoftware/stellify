<template>
    <component
        :style="[styles]"
        :class="classes"
        :is="computedTag"
        :src="items.src" 
        :width="items.width" 
        :height="items.height"
        :frameborder="items.frameborder" 
        :allowtransparency="items.allowtransparency"
        :allowpaymentrequest="items.allowpaymentrequest" 
        :referrerpolicy="items.referrerpolicy"
        :allow="items.allow ? items.allow : 'encrypted-media'">
    </component>
</template>

<script>
    export default {
        props: ['opts', 'settings', 'content'],
        data() {
            return {
                items: {}
            }
        },
        computed: {
            computedTag() {
                return this.items.tag ? this.items.tag : 'iframe'
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
            if (this.items.tag == 'div') {
                this.$set(this.items, 'tag', 'iframe');
            }
        }
    }
</script>
