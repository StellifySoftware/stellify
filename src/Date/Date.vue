<template>
    <div v-if="items.weekdayFormat || items.dayFormat || items.monthFormat || items.yearFormat" :class="classes">
        <p v-if="items.weekdayFormat" :class="weekdayClasses">
            {{ date[items.weekdayFormat] }}
        </p>
        <p v-if="items.dayFormat" :class="dayClasses">
            {{ date[items.dayFormat] }}
        </p>
        <p v-if="items.monthFormat" :class="monthClasses">
            {{ date[items.monthFormat] }}
        </p>
        <p v-if="items.yearFormat" :class="yearClasses">
            {{ date[items.yearFormat] }}
        </p>
    </div>
    <div v-else :class="classes">
        {{ formatted }}
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        props: ['opts', 'settings'],
        data() {
            return {
                items: {},
                now: new Date(),
                date: {},
            }
        },
        computed: {
            formatted() {
                if (this.items.format) {
                    return moment(this.now).format(this.items.format);
                }
                return moment().format('dddd Do MMMM YYYY');
            },
            classes() {
                if (typeof this.settings[this.items.type] != 'undefined' && this.settings[this.items.type].length > 0) {
                    return this.settings[this.items.type];
                }
                return this.items.classes;
            },
            dayClasses() {
                if (typeof this.settings[this.items.type] != 'undefined' && this.settings[this.items.type].length > 0) {
                    return this.settings[this.items.type];
                }
                return this.items.dayClasses;
            },
            weekdayClasses() {
                if (typeof this.settings[this.items.type] != 'undefined' && this.settings[this.items.type].length > 0) {
                    return this.settings[this.items.type];
                }
                return this.items.weekdayClasses;
            },
            monthClasses() {
                if (typeof this.settings[this.items.type] != 'undefined' && this.settings[this.items.type].length > 0) {
                    return this.settings[this.items.type];
                }
                return this.items.classes;
            },
            yearClasses() {
                if (typeof this.settings[this.items.type] != 'undefined' && this.settings[this.items.type].length > 0) {
                    return this.settings[this.items.type];
                }
                return this.items.yearClasses;
            }
        },
        beforeMount: function () {
            if (this.opts != null) {
                this.items = this.opts;
            }
            this.date.d = moment(this.now).format('d');
            this.date.dd = moment(this.now).format('dd');
            this.date.ddd = moment(this.now).format('ddd');
            this.date.dddd = moment(this.now).format('dddd');

            this.date.m = moment(this.now).format('M');
            this.date.mm = moment(this.now).format('MM');
            this.date.mmm = moment(this.now).format('MMM');
            this.date.mmmm = moment(this.now).format('MMMM');

            this.date.D = moment(this.now).format('D');
            this.date.DD = moment(this.now).format('DD');
            this.date.DDD = moment(this.now).format('DDD');

            this.date.yy = moment(this.now).format('YY');
            this.date.yyyy = moment(this.now).format('YYYY');
        }
    }
</script>
