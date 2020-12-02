<template>
    <component
        :is="computedTag"
        :name="items.name"
        :value="items.value"
        :src="items.src ? items.src : items.encoding"
        :alt="items.alt"
        :type="items.subtype"
        :href="items.href"
        :target="items.target"
        :title="items.title"
        :class="classes">
            {{ items.text }}
            <component
                v-for="(item, index) in items.data" 
                :key="index"
                :content="content"
                :settings="settings"
                :is="content[item].type"
                :class="content[item].classes"
                :opts="content[item]">
            </component>
    </component>
</template>

<script>
    export default {
        props: {
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
                items: {}
            }
        },
        computed: {
            computedTag() {
                return this.items.tag ? this.items.tag : 'div'
            },
            classes() {
                return this.items.classes;
            }
        },
        beforeMount: function () {
            if (this.opts != null) {
                this.items = this.opts;
            }
        }
    }
</script>