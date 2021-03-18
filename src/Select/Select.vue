<template>
    <select 
        v-if="content[record]" 
        v-model="content[source][items.field]" 
        :name="items.name" 
        :size="content[record] && items.sizeField ? content[record][items.sizeField] : items.size"
        :autofocus="content[record] && items.autofocusField ? content[record][items.autofocusField] : items.autofocus"
        :multiple="content[record] && items.multipleField ? content[record][items.multipleField] : items.multiple"
        :required="content[record] && items.requiredField ? content[record][items.requiredField] : items.required"
        :disabled="content[record] && items.disabledField ? content[record][items.disabledField] : items.disabled"
        :class="content[record] ? content[record][items.classesField] : null">
        <option 
            v-for="(item, index) in content[record].data" 
            :key="index" 
            :value="content[item].value">
            {{ content[item].text }}
        </option>
    </select>
    <select v-else 
        v-model="items.value" 
        :name="items.name" 
        :size="content[record] && items.sizeField ? content[record][items.sizeField] : items.size"
        :autofocus="content[record] && items.autofocusField ? content[record][items.autofocusField] : items.autofocus"
        :multiple="content[record] && items.multipleField ? content[record][items.multipleField] : items.multiple"
        :required="content[record] && items.requiredField ? content[record][items.requiredField] : items.required"
        :disabled="content[record] && items.disabledField ? content[record][items.disabledField] : items.disabled"
        :class="items.classes">
        <option 
            v-for="(item, index) in items.data" 
            :key="index" 
            :value="content[item].value">
            {{ content[item].text }}
        </option>
    </select>
</template>

<script>
    export default {
        name: 'SSelect',
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
        methods: {
            updateValue(value) {
                this.$set(this.items, 'value',  value);
                !this.isValid && this.checkHtml5Validity()
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
            }
        },
        beforeMount: function () {
            if (this.opts != null) {
                this.items = this.opts;
            }
        }
    }
</script>
