<template>
    <div :class="classes">
        <component 
            v-for="(item, index) in body.header" 
            :key="index"
            :body="body"
            :user="user"
            :content="content"
            :settings="settings"
            :is="content[item].type"
            :opts="content[item]">
        </component>
        <component 
            v-for="(item, index) in settings.header" 
            :key="index"
            :body="body"
            :user="user"
            :content="content"
            :settings="settings"
            :is="content[item].type"
            :opts="content[item]">
        </component>
        <component
            v-for="(item, index) in blocks" :key="index"
            :is="content[item].type"
            :content="content"
            :user="user"
            :body="body"
            :selection="selection"
            :settings="settings"
            :opts="content[item]">
        </component>
        <component 
            v-for="(item, index) in body.footer" 
            :key="index"
            :body="body"
            :content="content"
            :settings="settings"
            :is="content[item].type"
            :opts="content[item]">
        </component>
        <component 
            v-for="(item, index) in settings.footer" 
            :key="index"
            :body="body"
            :content="content"
            :settings="settings"
            :is="content[item].type"
            :opts="content[item]">
        </component>
    </div>
</template>

<script>
    export default {
        props: {
            body: {
                type: Object,
                default: function () { return {} }
            },
            blocks: {
                type: Array,
                default: []
            },
            user: {
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
            classes() {
                let classes = [];
                if (this.items.classes) {
                    classes = this.body.classes;
                }
                if (this.settings.classes) {
                    classes = classes.concat(this.settings.classes);
                }
                return classes;
            }
        }
    }
</script>
