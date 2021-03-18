<template>
    <div :class="classes"><span>{{ count ? count : 0 }}</span><span v-if="items.max"> / {{ items.max }}</span></div>
</template>

<script>
    export default {
        props: {
            record: {
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
                type: Object,
                default: function () { return {} }
            }
        },
        data() {
            return {
                items: {},
                count: 0
            }
        },
        watch: {
            content: {
                handler(val){
                    if (this.items.watch && this.content[this.items.watch]) {;
                        this.$set(this, 'count', this.content[this.items.watch].value.length);
                    }
                },
                deep: true
            }
        },
        computed: {
            computedTag() {
                return this.items.tag ? this.items.tag : 'div'
            },
            classes() {
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
        methods: {
            increase() {
                this.$set(this, 'count', this.count++);
            },
            mouseoverEvent() {
                if (this.items.mouseoverEvent) {
                    this.$root.$emit(this.items.mouseoverEvent, this.items);
                }
            },
            mouseleaveEvent() {
                if (this.items.mouseleaveEvent) {
                    this.$root.$emit(this.items.mouseleaveEvent, this.items);
                }
            },
            clickEvent() {
                if (this.items.clickEvent) {
                    this.$root.$emit(this.items.clickEvent, this.items);
                }
            }
        },
        beforeMount: function () {
            if (this.opts != null) {
                this.items = this.opts;
            }
            if (typeof this.items.visible == 'undefined') {
                this.items.visible = true;
            }
        },
        mounted() {
          if (this.items.eventListener) {
            this.$root.$on(this.items.eventListener, (item) => {
                console.log(item)
                if (item.eventType == 'showHide') {
                    this.$set(this.items, 'visible', !this.items.visible);
                }
                this.$forceUpdate();
            });
          }
        }
    }
</script>