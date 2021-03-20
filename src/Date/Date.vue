<template>
    <component
        :is="computedTag"
        :style="[styles]"
        :class="classes">
        {{ formatted }}
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
    import moment from 'moment';
    export default {
        props: ['opts', 'settings'],
        data() {
            return {
                items: {},
                duration: null
            }
        },
        computed: {
            _seconds: () => 1000,
            _minutes() {
                return this._seconds * 60;
            },
            _hours() {
                return this._minutes * 60;
            },
            _days() {
                return this._hours * 24;
            },
            formatted() {
                if (this.items.countdown && this.items.end) {
                    return this.duration;
                }
                return moment(this.items.datetime ? this.items.datetime : this.now).format(this.items.format ? this.items.format : 'dddd Do MMMM YYYY');
            },
            computedTag() {
                return this.items.tag ? this.items.tag : 'div'
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
        methods: {
            countdown() {
                if (this.items.end) {
                    const timer = setInterval(() => {
                        const now = new Date();
                        const end = new Date(this.items.end);
                        const duration = end.getTime() - now.getTime();
                        if (this.items.unit == 'Seconds') {
                            this.duration = Math.floor((duration % this._minutes) / this._seconds);
                        } else if (this.items.unit == 'Minutes') {
                            this.duration = Math.floor((duration % this._hours) / this._minutes);
                        } else if (this.items.unit == 'Hours') {
                            this.duration = Math.floor((duration % this._days) / this._hours);
                        } else if (this.items.unit == 'Days') {
                            this.duration = Math.floor(duration / this._days);
                        }
                    }, 1000);
                }
            }
        },
        beforeMount: function () {
            if (this.opts != null) {
                this.items = this.opts;
            }
        },
        mounted() {
            this.countdown();
        }
    }
</script>
