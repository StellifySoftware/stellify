<template>
    <component
        :style="[styles]"
        :class="[enabledClasses, classes]"
        :is="computedTag"
        ref="input"
        :maxlength="items.maxlength"
        :max="items.max"
        :pattern="items.pattern"
        :minlength="items.minlength"
        :min="items.min"
        :size="items.size"
        :accept="items.accept"
        :autofocus="items.autofocus"
        :list="items.list"
        :disabled="items.disabled"
        :value="items.value"
        :step="items.step"
        :required="items.required"
        :placeholder="items.placeholder"
        :autocomplete="content[record] && items.autocompleteField ? content[record][items.autocompleteField] : items.autocomplete"
        :type="items.inputType" 
        :name="content[record] && items.nameField ? content[record][items.nameField] : items.name" 
        v-model="items.value"
        @input="onInput"
        @change="onChange"
        @blur="onBlur"
        @focus="onFocus" 
        @click="clickHandler"
        @mouseover="mouseoverEvent"
        @mouseleave="mouseleaveEvent">
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
</template>

<script>
    import StellifyFormMixin from '../utils/StellifyFormMixin'
    export default {
        name: 'SInput',
        inheritAttrs: false,
        provide() {
            return {
                'SInput': this
            }
        },
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
        mixins: [StellifyFormMixin],
        data() {
            return {
                passwordHidden: true,
                items: {},
                charCount: 0
            }
        },
        watch: {
            content: {
                handler(val){
                    if (this.items.displayValueLength && this.items.target && this.content[this.items.target]) {
                        this.$set(this.items, 'text', this.content[this.items.target].value.length);
                    }
                    if (this.items.target && this.items.targetField && this.content[this.items.target]) {
                        this.$set(this.items, this.items.targetField, this.content[this.items.target][this.items.targetField]);
                    }
                },
                deep: true
            }
        },
        computed: {
            computedTag() {
                return this.items.tag
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
            valueLength() {
                if (this.items.hasCounter) {
                    if (typeof this.items.value === 'string') {
                        this.$set(this, 'charCount',  this.items.value.length);
                    } else if (typeof this.items.value === 'number') {
                        this.$set(this, 'charCount',  this.items.value.toString().length);
                    }
                }
                this.$forceUpdate();
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
            updateValue(value) {
                this.$set(this.items, 'value',  value);
                !this.isValid && this.checkHtml5Validity()
            },
            updateCount() {
                if (typeof this.items.count == 'undefined') {
                    this.$set(this.items, 'count', 0);
                }
                this.$set(this.items, 'count', this.items.count += this.items.step ? Number(this.items.step) : 1);
                this.$forceUpdate();
            },
            onInput(event) {
                if (!this.items.lazy) {
                    const value = event.target.value;
                    this.updateValue(value)
                }
            },
            onChange(event) {
                if (this.items.lazy) {
                    const value = event.target.value;
                    this.updateValue(value)
                }
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
            clickHandler() {
                this.items.enabled = !this.items.enabled;
                if (this.items.clickEvent) {
                    this.$root.$emit(this.items.clickEvent, this.items);
                } else if (typeof this.content[this.items.target] != 'undefined' && typeof this.items.targetField != 'undefined') {
                    if (this.items.toggleState) {
						this.$set(this.content[this.items.target], this.items.targetField, this.content[this.items.target][this.items.targetField] == this.items.toggleState[0] ? this.items.toggleState[1] : this.items.toggleState[0]);
					} else {
                        console.log(this.items.target, this.items.targetField)
                        this.$set(this.content[this.items.target], this.items.targetField, this.content[this.items.target][this.items.targetField] == true ? false : true);
                    }
				}
                if (this.items.recordCount) {
                    this.updateCount();
                }
                this.$forceUpdate();
            }
        },
        beforeMount: function () {
            if (this.opts != null) {
                this.items = this.opts;
            }
            if (this.items.tag == 'div') {
                this.$set(this.items, 'tag', 'input');
            }
            // if (this.items.target && this.content[this.items.target] && this.items.targetField) {
            //     if (this.items.toggleState) {
            //         this.$set(this.content[this.items.target], this.items.targetField, this.content[this.items.target][this.items.targetField] == this.items.toggleState[0] ? this.items.toggleState[1] : this.items.toggleState[0]);
            //     } else {
            //         this.$set(this.content[this.items.target], this.items.targetField, this.content[this.items.target][this.items.targetField] == true ? false : true);
            //     }
            // }
        }
    }
</script>
