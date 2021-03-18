<template>
    <div :class="appWrapperClasses">
        <div :class="pageWrapperClasses">
           <header v-if="items.header" :class="headerWrapperClasses">
                <component 
                    v-for="(item, index) in items.header" 
                    :key="index"
                    :body="body"
                    :user="user"
                    :content="content"
                    :settings="settings"
                    :is="content[item].type"
                    :opts="content[item]">
                </component>
            </header>
            <header v-else-if="settings.header" :class="headerWrapperClasses">
                <component 
                    v-for="(item, index) in settings.header" 
                    :key="index"
                    :body="body"
                    :user="user"
                    :content="content"
                    :settings="settings"
                    :is="content[item].type"
                    :opts="content[item]">
                </component>
            </header>
            <main v-if="blocks" :class="mainWrapperClasses">
                <component
                    v-for="(item, index) in blocks" :key="index + item"
                    :is="content[item].type"
                    :content="content"
                    :user="user"
                    :indicator="indicator"
                    :body="body"
                    :settings="settings"
                    :opts="content[item]">
                </component>
            </main>
            <footer v-if="items.footer" :class="footerWrapperClasses">
                <component 
                    v-for="(item, index) in items.footer" 
                    :key="index"
                    :body="body"
                    :content="content"
                    :settings="settings"
                    :is="content[item].type"
                    :opts="content[item]">
                </component>
            </footer>
            <footer v-else-if="settings.footer" :class="footerWrapperClasses">
                <component 
                    v-for="(item, index) in settings.footer" 
                    :key="index"
                    :body="body"
                    :content="content"
                    :settings="settings"
                    :is="content[item].type"
                    :opts="content[item]">
                </component>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            indicator: {
                type: String,
                default: null
            },
            selection: {
                default: true
            },
            body: {
                type: Object,
                default: function () { return {} }
            },
            users: {
                type: Array,
                default: []
            },
            blocks: {
                type: [Array, Object],
                default: function () { return {} }
            },
            user: null,
            settings: {
                type: Object,
                default: function () { return {} }
            },
            content: {
                type: [Array, Object],
                default: function () { return {} }
            }
        },
        data() {
            return {
                items: {},
                selected: 0,
                mouseY: 0,
                state: {},
                options: {}
            }
        },
        computed: {
            appWrapperClasses() {
                let classes = [];
                if (this.items.appWrapperClasses) {
                    classes = this.items.appWrapperClasses;
                }
                if (this.settings.appWrapperClasses) {
                    classes = classes.concat(this.settings.appWrapperClasses);
                }
                return classes;
            },
            pageWrapperClasses() {
                let classes = [];
                if (this.items.pageWrapperClasses) {
                    classes = this.items.pageWrapperClasses;
                }
                if (this.settings.editMode) {
                    classes = ['mb-12']
                }
                if (this.settings.pageWrapperClasses) {
                    classes = classes.concat(this.settings.pageWrapperClasses);
                }
                return classes;
            },
            headerWrapperClasses() {
                let classes = [];
                if (this.settings.editMode) {
                    classes = this.editOutlineClasses
                }
                if (this.items.headerWrapperClasses) {
                    classes = this.items.headerWrapperClasses;
                }
                if (this.settings.headerWrapperClasses) {
                    classes = classes.concat(this.settings.headerWrapperClasses);
                }
                return classes;
            },
            mainWrapperClasses() {
                let classes = [];
                if (this.settings.editMode) {
                    classes = this.editOutlineClasses
                }
                if (this.items.mainWrapperClasses) {
                    classes = this.items.mainWrapperClasses;
                }
                if (this.settings.mainWrapperClasses) {
                    classes = classes.concat(this.settings.mainWrapperClasses);
                }
                return classes;
            },
            computedTag() {
                return this.items.tag ? this.items.tag : 'div'
            },
            wrapperClasses() {
                let classes = [];
                if (this.items.wrapperClasses) {
                    classes = this.items.wrapperClasses;
                }
                if (this.settings.wrapperClasses) {
                    classes = classes.concat(this.settings.wrapperClasses);
                }
                return classes;
            },
            componentClasses() {
                let classes = [];
                if (this.items.componentClasses) {
                    classes = this.items.componentClasses;
                }
                if (this.settings.componentClasses) {
                    classes = classes.concat(this.settings.componentClasses
                    );
                }
                return classes;
            },
            footerWrapperClasses() {
                let classes = [];
                if (this.settings.editMode) {
                    classes = this.editOutlineClasses
                }
                if (this.items.footerWrapperClasses) {
                    classes = this.items.footerWrapperClasses;
                }
                if (this.settings.footerWrapperClasses) {
                    classes = classes.concat(this.settings.footerWrapperClasses);
                }
            }
        },
        methods: {
            resetState() {
                this.state = {};
            },
            closeNotice() {
                localStorage.setItem('viewedNotice', true);
            }
        },
        beforeMount() {
            if (this.body != null) {
                this.items = Object.assign({}, this.body);
                this.options = JSON.parse(this.body.data);
                let data = JSON.parse(this.body.data);
                if (typeof data != 'undefined') {
                    this.items.data = data.data;
                } else {
                    throw error;
                }
            }
            // if (this.user.length == 0, !localStorage.viewedNotice) {
            //     this.$skeleton.snackbar.open({
            //         indefinite: true,
            //         onAction: () => {
            //             this.closeNotice();
            //         }
            //     });
            // }
        }
    }
</script>

<style>
/* .mozaic {
    background-image: url("data:image/svg+xml,%3Csvg width='80' height='88' viewBox='0 0 80 88' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M22 21.91V26h-2c-9.94 0-18 8.06-18 18 0 9.943 8.058 18 18 18h2v4.09c8.012.722 14.785 5.738 18 12.73 3.212-6.99 9.983-12.008 18-12.73V62h2c9.94 0 18-8.06 18-18 0-9.943-8.058-18-18-18h-2v-4.09c-8.012-.722-14.785-5.738-18-12.73-3.212 6.99-9.983 12.008-18 12.73zM54 58v4.696c-5.574 1.316-10.455 4.428-14 8.69-3.545-4.262-8.426-7.374-14-8.69V58h-5.993C12.27 58 6 51.734 6 44c0-7.732 6.275-14 14.007-14H26v-4.696c5.574-1.316 10.455-4.428 14-8.69 3.545 4.262 8.426 7.374 14 8.69V30h5.993C67.73 30 74 36.266 74 44c0 7.732-6.275 14-14.007 14H54zM42 88c0-9.94 8.06-18 18-18h2v-4.09c8.016-.722 14.787-5.738 18-12.73v7.434c-3.545 4.262-8.426 7.374-14 8.69V74h-5.993C52.275 74 46 80.268 46 88h-4zm-4 0c0-9.943-8.058-18-18-18h-2v-4.09c-8.012-.722-14.785-5.738-18-12.73v7.434c3.545 4.262 8.426 7.374 14 8.69V74h5.993C27.73 74 34 80.266 34 88h4zm4-88c0 9.943 8.058 18 18 18h2v4.09c8.012.722 14.785 5.738 18 12.73v-7.434c-3.545-4.262-8.426-7.374-14-8.69V14h-5.993C52.27 14 46 7.734 46 0h-4zM0 34.82c3.213-6.992 9.984-12.008 18-12.73V18h2c9.94 0 18-8.06 18-18h-4c0 7.732-6.275 14-14.007 14H14v4.696c-5.574 1.316-10.455 4.428-14 8.69v7.433z' fill='%238d8d8d' fill-opacity='0.25' fill-rule='evenodd'/%3E%3C/svg%3E");
} */
.form-textarea, .form-input {
    appearance: none;
    background-color: #fff;
    border-color: #d2d6dc;
    border-width: 1px;
    font-size: 1rem;
    line-height: 1.5;
}
.form-select {
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke='%239fa6b2' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    appearance: none;
    color-adjust: exact;
    background-repeat: no-repeat;
    background-color: #fff;
    border-color: #d2d6dc;
    border-width: 1px;
    border-radius: .375rem;
    padding: .5rem 2.5rem .5rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    background-position: right .5rem center;
    background-size: 1.5em 1.5em;
}
[type='text'], [type='email'], [type='url'], [type='password'], [type='number'], [type='date'], [type='month'], [type='search'], [type='time'], textarea, select {
    appearance: none;
    background-color: #fff;
    border-width: 1px;
    padding-top: 0.5rem;
    padding-right: 0.75rem;
    padding-bottom: 0.5rem;
    padding-left: 0.75rem;
    font-size: 1rem;
    line-height: 1.5rem;
}
</style>
