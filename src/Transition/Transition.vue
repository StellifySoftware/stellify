<template>
    <transition 
        :name="items.transitionName ? items.transitionName : currentTransition"
        :enter-active-class="enterActiveClasses"
        :enter-class="enterFromClasses"
        :enter-to-class="enterToClasses"
        :leave-active-class="leaveActiveClasses"
        :leave-class="leaveFromClasses"
        :leave-to-class="leaveToClasses"
        :mode="items.mode ? items.mode : 'out-in'">
        <div v-if="items.source && items.visible" :class="wrapperClasses">
            <component
                v-for="(record, index) in content[items.source].data" 
                :key="index"
                :is="computedTag"
                :name="content[record] ? content[record][items.nameField] : items.name"
                :value="content[record] ? content[record][items.valueField] : items.value"
                :src="content[record] ? content[record][items.srcField] : items.src"
                :alt="content[record] ? content[record][items.altField] : items.alt"
                :type="content[record] ? content[record][items.subtypeField] : items.subtype"
                :href="content[record] ? content[record][items.hrefField] : items.href"
                :target="content[record] ? content[record][items.targetField] : items.target"
                :title="content[record] ? content[record][items.titleField] : items.title"
                :class="classes">
                {{ typeof items.field != 'undefined' ? content[record][items.field] : items.text }}
                <component
                    v-for="(item, index) in items.data" 
                    :key="index"
                    :content="content"
                    :record="record"
                    :settings="settings"
                    :is="content[item].type"
                    :class="content[item].componentClasses"
                    :opts="content[item]">
                </component>
            </component>
        </div>
        <component
            v-else-if="items.visible"
            :is="computedTag"
            :style="[styles]"
            :name="content[record] ? content[record][items.nameField] : items.name"
            :value="content[record] ? content[record][items.valueField] : items.value"
            :src="content[record] ? content[record][items.srcField] : items.src"
            :alt="content[record] ? content[record][items.altField] : items.alt"
            :type="content[record] ? content[record][items.subtypeField] : items.subtype"
            :href="content[record] ? content[record][items.hrefField] : items.href"
            :target="content[record] ? content[record][items.targetField] : items.target"
            :title="content[record] ? content[record][items.titleField] : items.title"
            :class="classes">
                {{ typeof items.field != 'undefined' ? content[record][items.field] : items.text }}
                <component
                    v-for="(item, index) in items.data" 
                    :key="index"
                    :content="content"
                    :record="record"
                    :settings="settings"
                    :is="content[item].type"
                    :class="content[item].componentClasses"
                    :opts="content[item]">
                </component>
        </component>
    </transition>
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
                currentTransition: null
            }
        },
        computed: {
            enterFromClasses() {
                if (this.items.enterClasses) {
                    return this.items.enterClasses.join(' ');
                }
            },
            enterActiveClasses() {
                if (this.items.enterActiveClasses) {
                    return this.items.enterActiveClasses.join(' ');
                }
            },
            enterToClasses() {
                if (this.items.enterToClasses) {
                    return this.items.enterToClasses.join(' ');
                }
            },
            leaveFromClasses() {
                if (this.items.leaveClasses) {
                    return this.items.leaveClasses.join(' ');
                }
            },
            leaveActiveClasses() {
                if (this.items.leaveActiveClasses) {
                    return this.items.leaveActiveClasses.join(' ');
                }
            },
            leaveToClasses() {
                if (this.items.leaveToClasses) {
                    return this.items.leaveToClasses.join(' ');
                }
            },
            computedTag() {
                return this.items.tag ? this.items.tag : 'div'
            },
            classes() {
                return this.items.classes;
            },
            styles() {
                return {
                    minHeight: this.items.minHeight
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