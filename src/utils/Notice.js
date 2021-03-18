import Notice from '../Notice'
import config, { VueInstance } from './config'
import { merge } from './helpers'
import { use, registerComponentProgrammatic } from './plugins'

let localVueInstance

const SnackbarProgrammatic = {
    open(params) {
        let parent
        if (typeof params === 'string') {
            params = {
                message: params
            }
        }

        const defaultParam = {
            type: 'is-success',
            position: config.defaultSnackbarPosition || 'is-bottom-right'
        }
        if (params.parent) {
            parent = params.parent
            delete params.parent
        }
        let slot
        if (Array.isArray(params.message)) {
            slot = params.message
            delete params.message
        }
        const propsData = merge(defaultParam, params)
        const vm = typeof window !== 'undefined' && window.Vue ? window.Vue : localVueInstance || VueInstance
        const SnackbarComponent = vm.extend(Notice)
        const component = new SnackbarComponent({
            parent,
            el: document.createElement('div'),
            propsData
        })
        if (slot) {
            component.$slots.default = slot
            component.$forceUpdate()
        }
        return component
    }
}

const Plugin = {
    install(Vue) {
        localVueInstance = Vue
        registerComponentProgrammatic(Vue, 'notice', SnackbarProgrammatic)
    }
}

use(Plugin)

export default Plugin

export {
    SnackbarProgrammatic,
    Notice as SNotice
}