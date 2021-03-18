<template>
    <transition
        :name="animation"
        @after-enter="afterEnter"
        @before-leave="beforeLeave"
        @after-leave="afterLeave"
    >
        <div
            v-if="!destroyed"
            v-show="isActive"
            class="modal is-active"
            :class="[{'is-full-screen': fullScreen}, customClass]"
            v-trap-focus="trapFocus"
            tabindex="-1"
            :role="ariaRole"
            :aria-modal="ariaModal">
            <div class="absolute inset-0 bg-gray-500 opacity-75" @click="cancel('outside')"/>
            <div
                class="animation-content"
                :class="{ 'modal-content': !hasModalCard }"
                :style="customStyle">
                <component
                    v-if="component"
                    v-bind="props"
                    v-on="events"
                    :is="component"
                    :can-cancel="canCancel"
                    @close="close"
                />
                <template v-else-if="content">
                    <div v-html="content" />
                </template>
                <slot
                    v-else
                    :can-cancel="canCancel"
                    :close="close"/>
                <button
                    type="button"
                    v-if="showX"
                    v-show="!animating"
                    class="absolute top-0 right-0 pt-4 pr-4 rounded-md text-white hover:text-gray-500 focus:outline-none z-50"
                    @click="cancel('x')">
                    <svg class="h-6 w-6" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </transition>
</template>

<script>
import trapFocus from '../directives/trapFocus.js'
import { removeElement } from '../utils/helpers'
import config from '../utils/config'

export default {
    name: 'SModal',
    directives: {
        trapFocus
    },
    // deprecated, to replace with default 'value' in the next breaking change
    model: {
        prop: 'active',
        event: 'update:active'
    },
    props: {
        active: Boolean,
        component: [Object, Function, String],
        content: [String, Array],
        programmatic: Boolean,
        props: Object,
        events: Object,
        width: {
            type: [String, Number],
            default: 960
        },
        hasModalCard: Boolean,
        animation: {
            type: String,
            default: 'zoom-out'
        },
        canCancel: {
            type: [Array, Boolean],
            default: () => {
                return true
            }
        },
        onCancel: {
            type: Function,
            default: () => {}
        },
        scroll: {
            type: String,
            default: () => {
                return config.defaultModalScroll
                    ? config.defaultModalScroll
                    : 'clip'
            },
            validator: (value) => {
                return [
                    'clip',
                    'keep'
                ].indexOf(value) >= 0
            }
        },
        fullScreen: Boolean,
        trapFocus: {
            type: Boolean,
            default: () => {
                return config.defaultTrapFocus
            }
        },
        autoFocus: {
            type: Boolean,
            default: () => {
                return config.defaultAutoFocus
            }
        },
        customClass: String,
        ariaRole: {
            type: String,
            validator: (value) => {
                return [
                    'dialog',
                    'alertdialog'
                ].indexOf(value) >= 0
            }
        },
        ariaModal: Boolean,
        destroyOnHide: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            isActive: this.active || false,
            savedScrollTop: null,
            newWidth: typeof this.width === 'number'
                ? this.width + 'px'
                : this.width,
            animating: true,
            destroyed: !this.active
        }
    },
    computed: {
        cancelOptions() {
            return ['escape', 'x', 'outside']
        },
        showX() {
            return this.cancelOptions.indexOf('x') >= 0 && !this.hasModalCard
        },
        customStyle() {
            if (!this.fullScreen) {
                return { maxWidth: this.newWidth }
            }
            return null
        }
    },
    watch: {
        active(value) {
            this.isActive = value
        },
        isActive(value) {
            if (value) this.destroyed = false
            this.handleScroll()
            this.$nextTick(() => {
                if (value && this.$el && this.$el.focus && this.autoFocus) {
                    this.$el.focus()
                }
            })
        }
    },
    methods: {
        handleScroll() {
            if (typeof window === 'undefined') return

            if (this.scroll === 'clip') {
                if (this.isActive) {
                    document.documentElement.classList.add('is-clipped')
                } else {
                    document.documentElement.classList.remove('is-clipped')
                }
                return
            }

            this.savedScrollTop = !this.savedScrollTop
                ? document.documentElement.scrollTop
                : this.savedScrollTop

            if (this.isActive) {
                document.body.classList.add('is-noscroll')
            } else {
                document.body.classList.remove('is-noscroll')
            }

            if (this.isActive) {
                document.body.style.top = `-${this.savedScrollTop}px`
                return
            }

            document.documentElement.scrollTop = this.savedScrollTop
            document.body.style.top = null
            this.savedScrollTop = null
        },

        /**
        * Close the Modal if canCancel and call the onCancel prop (function).
        */
        cancel(method) {
            console.log('czncer')
            if (this.cancelOptions.indexOf(method) < 0) return
            this.$emit('cancel', arguments)
            this.onCancel.apply(null, arguments)
            this.close()
        },

        /**
        * Call the onCancel prop (function).
        * Emit events, and destroy modal if it's programmatic.
        */
        close() {
            this.$emit('close')
            this.$emit('update:active', false)

            // Timeout for the animation complete before destroying
            if (this.programmatic) {
                this.isActive = false
                setTimeout(() => {
                    this.$destroy()
                    removeElement(this.$el)
                }, 150)
            }
        },

        /**
        * Keypress event that is bound to the document.
        */
        keyPress({ key }) {
            if (this.isActive && (key === 'Escape' || key === 'Esc')) this.cancel('escape')
        },

        /**
        * Transition after-enter hook
        */
        afterEnter() {
            this.animating = false
            this.$emit('after-enter')
        },

        /**
        * Transition before-leave hook
        */
        beforeLeave() {
            this.animating = true
        },

        /**
        * Transition after-leave hook
        */
        afterLeave() {
            if (this.destroyOnHide) {
                this.destroyed = true
            }
            this.$emit('after-leave')
        }
    },
    created() {
        if (typeof window !== 'undefined') {
            document.addEventListener('keyup', this.keyPress)
        }
    },
    beforeMount() {
        // Insert the Modal component in body tag
        // only if it's programmatic
        this.programmatic && document.body.appendChild(this.$el)
    },
    mounted() {
        if (this.programmatic) this.isActive = true
        else if (this.isActive) this.handleScroll()
    },
    beforeDestroy() {
        if (typeof window !== 'undefined') {
            document.removeEventListener('keyup', this.keyPress)
            // reset scroll
            document.documentElement.classList.remove('is-clipped')
            const savedScrollTop = !this.savedScrollTop
                ? document.documentElement.scrollTop
                : this.savedScrollTop
            document.body.classList.remove('is-noscroll')
            document.documentElement.scrollTop = savedScrollTop
            document.body.style.top = null
        }
    }
}
</script>
