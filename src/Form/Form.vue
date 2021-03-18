<template>
    <div v-if="items.source" :class="classes" v-show="items.visible">
        <form
            v-for="(record, index) in content[items.source].data" 
            :key="index"
            :action="content[record] && items.action ? content[record][items.action] : items.action" 
            :method="content[method] && items.method ? content[record][items.method] : items.method" 
            :enctype="content[method] && items.enctype ? content[record][items.enctype] : items.enctype" 
            :id="content[record] && items.id ? content[record][items.id] : items.id"
            :class="[content[record] ? content[record][items.classesField] : null, {'border border-red-400': indicator == items.slug}]">
            <input type="hidden" name="_token" :value="csrf">
            <input v-if="content[method] && items.slack ? content[record][items.slack] : items.slack" type="hidden" name="notify" value="true">
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
        </form>
    </div>
    <form
        v-else
        v-show="items.visible"
        :style="[styles]"
        :action="content[record] && items.action ? content[record][items.action] : items.action" 
        :method="content[record] && items.method ? content[record][items.method] : items.method" 
        :enctype="content[record] && items.enctype ? content[record][items.enctype] : items.enctype" 
        :id="content[record] && items.id  ? content[record][items.id] : items.id"
        :class="[classes, {'border border-red-400': indicator == items.slug}]">
            <input type="hidden" name="_token" :value="csrf">
            <input v-if="content[record] && items.slack ? content[record][items.slack] : items.slack" type="hidden" name="notify" value="true">
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
    </form>
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
                items: {},
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        computed: {
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
            if (typeof this.items.visible == 'undefined') {
                this.items.visible = true;
            }
        }
    }
</script>