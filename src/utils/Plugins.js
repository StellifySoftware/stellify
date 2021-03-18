export const use = (plugin) => {
    if (typeof window !== 'undefined' && window.Vue) {
        window.Vue.use(plugin)
    }
}

export const registerComponentProgrammatic = (Vue, property, component) => {
    if (!Vue.prototype.$skeleton) Vue.prototype.$skeleton = {}
    Vue.prototype.$skeleton[property] = component
}