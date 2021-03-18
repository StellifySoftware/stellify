<template>
    <div
        class="dropdown dropdown-menu-animation"
        ref="dropdown"
        :class="rootClasses"
    >
        <div
            v-if="!inline"
            role="button"
            ref="trigger"
            class="dropdown-trigger"
            @click="onClick"
            @contextmenu.prevent="onContextMenu"
            @mouseenter="onHover"
            @focus.capture="onFocus"
            aria-haspopup="true">
            <component
                :is="computedTag"
                :class="classes">
                {{ typeof items.field != 'undefined' ? content[record][items.field] : items.text }}
            </component>
        </div>

        <transition :name="animation">
            <div
                v-if="isMobileModal"
                v-show="isActive"
                class="background"
                :aria-hidden="!isActive"
            />
        </transition>
        <transition :name="animation">
            <div
                v-show="(!disabled && (isActive || isHoverable)) || inline"
                ref="dropdownMenu"
                :class="secondaryClasses"
                :style="style"
                :aria-hidden="!isActive"
                v-trap-focus="trapFocus">
                <div v-if="items.source">
                    <div
                        v-for="(record, index) in content[items.source].data" 
                        :key="index"
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
                    </div>
                </div>
                <component
                    v-else
                    v-for="(item, index) in items.data" 
                    :key="index"
                    :content="content"
                    :settings="settings"
                    :is="content[item].type"
                    :class="content[item].componentClasses"
                    :opts="content[item]">
                </component>
            </div>
        </transition>
    </div>
</template>

<script>
import trapFocus from '../directives/trapFocus.js'
import config from '../utils/config'
import { removeElement, createAbsoluteElement, isCustomElement, toCssWidth } from '../utils/helpers'
const DEFAULT_CLOSE_OPTIONS = ['escape', 'outside']
export default {
    name: 'SDropdown',
    directives: {
        trapFocus
    },
    props: {
        value: {
            type: [String, Number, Boolean, Object, Array, Function],
            default: null
        },
        disabled: Boolean,
        inline: Boolean,
        scrollable: Boolean,
        maxHeight: {
            type: [String, Number],
            default: 200
        },
        settings: {
            type: Object,
            default: function () { return {} }
        },
        content: {
            type: Object,
            default: function () { return {} }
        },
        opts: {
            type: Object,
            default: function () { return {} }
        },
        position: {
            type: String,
            validator(value) {
                return [
                    'is-top-right',
                    'is-top-left',
                    'is-bottom-left',
                    'is-bottom-right'
                ].indexOf(value) > -1
            }
        },
        triggers: {
            type: Array,
            default: () => ['click']
        },
        mobileModal: {
            type: Boolean,
            default: () => {
                return config.defaultDropdownMobileModal
            }
        },
        ariaRole: {
            type: String,
            validator(value) {
                return [
                    'menu',
                    'list',
                    'dialog'
                ].indexOf(value) > -1
            },
            default: null
        },
        animation: {
            type: String,
            default: 'fade'
        },
        multiple: Boolean,
        trapFocus: {
            type: Boolean,
            default: () => {
                return config.defaultTrapFocus
            }
        },
        closeOnClick: {
            type: Boolean,
            default: true
        },
        canClose: {
            type: [Array, Boolean],
            default: true
        },
        expanded: Boolean,
        appendToBody: Boolean,
        appendToBodyCopyParent: Boolean
    },
    data() {
        return {
            selected: this.value,
            style: {},
            items: {},
            isActive: false,
            isHoverable: false,
            _bodyEl: undefined // Used to append to body
        }
    },
    computed: {
        computedTag() {
            return this.items.tag ? this.items.tag : 'div'
        },
        classes() {
            return this.items.classes;
        },
        secondaryClasses() {
            return this.items.secondaryClasses ? this.items.secondaryClasses : 'origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5';
        },
        rootClasses() {
            return [this.position, {
                'is-disabled': this.disabled,
                'is-hoverable': this.hoverable,
                'is-inline': this.inline,
                'is-active': this.isActive || this.inline,
                'is-mobile-modal': this.isMobileModal,
                'is-expanded': this.expanded
            }]
        },
        isMobileModal() {
            return this.mobileModal && !this.inline
        },
        cancelOptions() {
            return typeof this.canClose === 'boolean'
                ? this.canClose
                    ? DEFAULT_CLOSE_OPTIONS
                    : []
                : this.canClose
        },
        contentStyle() {
            return {
                maxHeight: this.scrollable ? toCssWidth(this.maxHeight) : null,
                overflow: this.scrollable ? 'auto' : null
            }
        },
        hoverable() {
            return this.triggers.indexOf('hover') >= 0
        }
    },
    watch: {
        /**
        * When v-model is changed set the new selected item.
        */
        value(value) {
            this.selected = value
        },
        /**
        * Emit event when isActive value is changed.
        */
        isActive(value) {
            this.$emit('active-change', value)
            if (this.appendToBody) {
                this.$nextTick(() => {
                    this.updateAppendToBody()
                })
            }
        }
    },
    methods: {
        /**
         * Click listener from DropdownItem.
         *   1. Set new selected item.
         *   2. Emit input event to update the user v-model.
         *   3. Close the dropdown.
         */
        selectItem(value) {
            if (this.multiple) {
                if (this.selected) {
                    if (this.selected.indexOf(value) === -1) {
                        // Add value
                        this.selected = [...this.selected, value]
                    } else {
                        // Remove value
                        this.selected = this.selected.filter((val) => val !== value)
                    }
                } else {
                    this.selected = [value]
                }
                this.$emit('change', this.selected)
            } else {
                if (this.selected !== value) {
                    this.selected = value
                    this.$emit('change', this.selected)
                }
            }
            this.$emit('input', this.selected)
            if (!this.multiple) {
                this.isActive = !this.closeOnClick
                if (this.hoverable && this.closeOnClick) {
                    this.isHoverable = false
                }
            }
        },
        /**
        * White-listed items to not close when clicked.
        */
        isInWhiteList(el) {
            if (el === this.$refs.dropdownMenu) return true
            if (el === this.$refs.trigger) return true
            // All chidren from dropdown
            if (this.$refs.dropdownMenu !== undefined) {
                const children = this.$refs.dropdownMenu.querySelectorAll('*')
                for (const child of children) {
                    if (el === child) {
                        return true
                    }
                }
            }
            // All children from trigger
            if (this.$refs.trigger !== undefined) {
                const children = this.$refs.trigger.querySelectorAll('*')
                for (const child of children) {
                    if (el === child) {
                        return true
                    }
                }
            }
            return false
        },
        /**
        * Close dropdown if clicked outside.
        */
        clickedOutside(event) {
            if (this.cancelOptions.indexOf('outside') < 0) return
            if (this.inline) return
            const target = isCustomElement(this) ? event.composedPath()[0] : event.target
            if (!this.isInWhiteList(target)) this.isActive = false
        },
        /**
         * Keypress event that is bound to the document
         */
        keyPress({ key }) {
            if (this.isActive && (key === 'Escape' || key === 'Esc')) {
                if (this.cancelOptions.indexOf('escape') < 0) return
                this.isActive = false
            }
        },
        onClick() {
            if (this.triggers.indexOf('click') < 0) return
            this.toggle()
        },
        onContextMenu() {
            if (this.triggers.indexOf('contextmenu') < 0) return
            this.toggle()
        },
        onHover() {
            if (this.triggers.indexOf('hover') < 0) return
            this.isHoverable = true
        },
        onFocus() {
            if (this.triggers.indexOf('focus') < 0) return
            this.toggle()
        },
        /**
        * Toggle dropdown if it's not disabled.
        */
        toggle() {
            if (this.disabled) return
            if (!this.isActive) {
                // if not active, toggle after clickOutside event
                // this fixes toggling programmatic
                this.$nextTick(() => {
                    const value = !this.isActive
                    this.isActive = value
                    // Vue 2.6.x ???
                    setTimeout(() => (this.isActive = value))
                })
            } else {
                this.isActive = !this.isActive
            }
        },
        updateAppendToBody() {
            const dropdown = this.$refs.dropdown
            const dropdownMenu = this.$refs.dropdownMenu
            const trigger = this.$refs.trigger
            if (dropdownMenu && trigger) {
                // update wrapper dropdown
                const dropdownWrapper = this.$data._bodyEl.children[0]
                dropdownWrapper.classList.forEach((item) => dropdownWrapper.classList.remove(item))
                dropdownWrapper.classList.add('dropdown')
                dropdownWrapper.classList.add('dropdown-menu-animation')
                if (this.$vnode && this.$vnode.data && this.$vnode.data.staticClass) {
                    dropdownWrapper.classList.add(this.$vnode.data.staticClass)
                }
                this.rootClasses.forEach((item) => {
                    // skip position prop
                    if (item && typeof item === 'object') {
                        for (let key in item) {
                            if (item[key]) {
                                dropdownWrapper.classList.add(key)
                            }
                        }
                    }
                })
                if (this.appendToBodyCopyParent) {
                    const parentNode = this.$refs.dropdown.parentNode
                    const parent = this.$data._bodyEl
                    parent.classList.forEach((item) => parent.classList.remove(item))
                    parentNode.classList.forEach((item) => {
                        parent.classList.add(item)
                    })
                }
                const rect = trigger.getBoundingClientRect()
                let top = rect.top + window.scrollY
                let left = rect.left + window.scrollX
                if (!this.position || this.position.indexOf('bottom') >= 0) {
                    top += trigger.clientHeight
                } else {
                    top -= dropdownMenu.clientHeight
                }
                if (this.position && this.position.indexOf('left') >= 0) {
                    left -= (dropdownMenu.clientWidth - trigger.clientWidth)
                }
                this.style = {
                    position: 'absolute',
                    top: `${top}px`,
                    left: `${left}px`,
                    zIndex: '99',
                    width: this.expanded ? `${dropdown.offsetWidth}px` : undefined
                }
            }
        }
    },
    beforeMount: function () {
        if (this.opts != null) {
            this.items = this.opts;
        }
    },
    mounted() {
        if (this.appendToBody) {
            this.$data._bodyEl = createAbsoluteElement(this.$refs.dropdownMenu)
            this.updateAppendToBody()
        }
    },
    created() {
        if (typeof window !== 'undefined') {
            document.addEventListener('click', this.clickedOutside)
            document.addEventListener('keyup', this.keyPress)
        }
    },
    beforeDestroy() {
        if (typeof window !== 'undefined') {
            document.removeEventListener('click', this.clickedOutside)
            document.removeEventListener('keyup', this.keyPress)
        }
        if (this.appendToBody) {
            removeElement(this.$data._bodyEl)
        }
    }
}
</script>