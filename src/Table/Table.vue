<template>
    <div v-if="items.source">
        <table class="min-w-full divide-y divide-gray-200">
            <thead v-if="content[items.source].fields">
                <tr>
                    <th
                        v-for="(column, index) in content[items.source].fields"
                        :key="index + 'header'"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ column.label }}
                    </th>
                </tr>
            </thead>
            <tbody v-if="content[items.source].data">
                <tr v-for="(row, index) in content[items.source].data" :key="index" :class="index % 2 ? 'bg-white' : 'bg-gray-50'">
                    <td v-for="(column, colindex) in content[items.source].fields" :key="index + ':' + colindex" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ content[row] ? content[row][column.field] : '' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else class="sm:flex sm:items-start p-3">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div class="flex text-center items-center justify-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
            We can't find any data. Please select a source.
            </h3>
        </div>
    </div>
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
                items: {}
            }
        },
        computed: {
            computedTag() {
                return this.items.tag ? this.items.tag : 'div'
            },
            wrapperClasses() {
                return this.items.wrapperClasses;
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