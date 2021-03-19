<template>
    <div v-if="items.source" :class="classes">
        <component
            v-for="(record, index) in content[items.source].data" 
            :key="index"
            :is="computedTag"
            :ref="items.ref"
            :name="content[record] && items.nameField ? content[record][items.nameField] : items.name"
            :value="content[record] && items.valueField  ? content[record][items.valueField] : items.value"
            :src="content[record] && items.srcField  ? content[record][items.srcField] : items.src"
            :for="content[record] && items.forField ? content[record][items.forField] : items.for"
            :label="content[record] && items.labelField  ? content[record][items.labelField] : items.label"
            :id="content[record] && items.idField  ? content[record][items.idField] : items.id"
            :alt="content[record] && items.altField ? content[record][items.altField] : items.alt"
            :download="content[record] && items.downloadField ? content[record][items.downloadField] : items.download"
            :referrerpolicy="content[record] && items.referrerpolicyField  ? content[record][items.referrerpolicyField] : items.referrerpolicy"
            :type="content[record] && items.subtypeField  ? content[record][items.subtypeField] : items.subtype"
            :href="content[record] && items.hrefField  ? content[record][items.hrefField] : items.href"
            :rel="content[record] && items.relField  ? content[record][items.relField] : items.rel"
            :target="content[record] && items.targetField  ? content[record][items.targetField] : items.target"
            :title="content[record] && items.titleField  ? content[record][items.titleField] : items.title"
            :class="[content[record] ? content[record][items.classesField] : null, {'border border-red-400': indicator == items.slug}]"
            @click.stop="clickHandler">
            {{ typeof items.field != 'undefined' ? content[record][items.field] : items.text }}
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
    </div>
    <component
        :is="computedTag"
        :style="[styles]"
        :class="[classes, enabledClasses, {'border border-red-400': indicator == items.slug}]"
        v-else-if="items.html"
        v-html="items.html"
        @click.stop="clickHandler">
    </component>
    <component
        v-else
        :is="computedTag"
        :style="[styles]"
        :ref="items.ref"
        :name="content[record] && items.nameField ? content[record][items.nameField] : items.name"
        :value="content[record] && items.valueField  ? content[record][items.valueField] : items.value"
        :src="content[record] && items.srcField  ? content[record][items.srcField] : items.src"
        :for="content[record] && items.forField ? content[record][items.forField] : items.for"
        :label="content[record] && items.labelField  ? content[record][items.labelField] : items.label"
        :id="content[record] && items.idField  ? content[record][items.idField] : items.id"
        :alt="content[record] && items.altField ? content[record][items.altField] : items.alt"
        :download="content[record] && items.downloadField ? content[record][items.downloadField] : items.download"
        :referrerpolicy="content[record] && items.referrerpolicyField  ? content[record][items.referrerpolicyField] : items.referrerpolicy"
        :type="content[record] && items.subtypeField  ? content[record][items.subtypeField] : items.subtype"
        :href="content[record] && items.hrefField  ? content[record][items.hrefField] : items.href"
        :rel="content[record] && items.relField  ? content[record][items.relField] : items.rel"
        :target="content[record] && items.targetField  ? content[record][items.targetField] : items.target"
        :title="content[record] && items.titleField  ? content[record][items.titleField] : items.title"
        :class="[classes, enabledClasses, {'border border-red-400': indicator == items.slug}]"
        @click.stop="clickHandler">
            {{ typeof items.field != 'undefined' ? content[record][items.field] : items.text }}
            <component
                v-for="(item, index) in items.data" 
                :key="index"
                :content="content"
                :record="record"
                :indicator="indicator"
                :state="state"
                :settings="settings"
                :is="content[item].type"
                :opts="content[item]">
            </component>
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
            state: {
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
        watch: {
            content: {
                handler(val){
                    if (this.items.displayValueLength && this.items.watch && this.content[this.items.watch]) {
                        this.$set(this.items, 'text', this.content[this.items.watch].value.length);
                    }
                    if (this.content[this.items.watch] && this.items.targetField) {
                        this.$set(this.items, this.items.targetField, this.content[this.items.watch][this.items.targetField]);
                    }
                },
                deep: true
            }
        },
        methods: {
            clickHandler() {
                this.$root.$emit('captureClick', this.items.slug);
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
            enabledClasses() {
                if (this.items.enabled) {
                    return this.items.enabledClasses;
                }
            },
            styles() {
                return {
                    fontFamily: this.items.fontFamily,
                    minHeight: this.items.minHeight,
                    height: this.items.height,
                    backgroundPosition: this.items.backgroundPosition,
                    backgroundSize: this.items.backgroundSize,
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