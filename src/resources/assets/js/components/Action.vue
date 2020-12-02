<template>
	<button @click.stop="clickHandler" type="button" :class="classes">
		{{ items.text }}
		<component
			v-for="(item, index) in items.data" 
			:key="index"
			:content="content"
			:settings="settings"
			:is="content[item].type"
			:class="content[item].classes"
			:opts="content[item]">
		</component>
	</button>
</template>

<script>
	export default {
		props: {
			opts: {
				type: Object,
				default: function () { return {} }
			},
			settings: {
				type: Object,
				default: function () { return {} }
			},
			content: {
				type: Object,
				default: function () { return {} }
			}
		},
		data() {
			return {
				items: {},
			};
		},
		computed: {
            classes() {
                return this.items.classes;
            }
        },
		methods: {
			clickHandler(event) {
				if (this.items.rootEvent) {
					this.$root.$emit(this.items.rootEvent);
				}
			}
		},
		beforeMount: function() {
			this.items = this.opts;
		},
	};
</script>
