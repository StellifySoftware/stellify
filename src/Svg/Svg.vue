<template>
	<svg
		v-if="items.path || content[record]"
		:class="[classes, enabledClasses]"
		xmlns="http://www.w3.org/2000/svg"
		xmlns:xlink="http://www.w3.org/1999/xlink"
        :width="items.width"
        :height="items.height"
        :viewBox="items.viewBox"
        :fill="items.fill"
		:stroke="items.stroke"
		:preserveAspectRatio="items.preserveAspectRatio"
        :aria-labelledby="items.name"
        :role="items.presentation"
		v-html="content[record] && items.pathField ? content[record][items.pathField] : items.path">
	</svg>
    <svg 
		v-else-if="items.paths"
		:class="[classes, enabledClasses]"
		xmlns="http://www.w3.org/2000/svg"
		xmlns:xlink="http://www.w3.org/1999/xlink"
        :width="items.width"
        :height="items.height"
        :viewBox="items.viewBox"
        :fill="items.fill"
		:stroke="items.stroke"
		:preserveAspectRatio="items.preserveAspectRatio"
        :aria-labelledby="items.name"
        :role="items.presentation">
		<title :id="items.name" :lang="items.lang ? items.lang : 'en'">{{ items.name }}</title>
		<defs>
			<linearGradient id="linearGradient" x2="1" y2="1">
				<stop offset="0%" stop-color="var(--tw-gradient-from)" />
				<stop offset="100%" stop-color="var(--tw-gradient-to)" />
			</linearGradient>
			<filter id="filter" x="0" y="0">
				<feGaussianBlur :in="items.feGaussianBlurIn" :stdDeviation="items.feGaussianBlurStdDeviation" />
			</filter>
		</defs>
		<g v-if="items.text" :transform="items.textTransform">
			<text id="textElement" x="0" y="0" :style="items.fontStyle">{{ items.text }}
				<animateMotion :path="items.animateMotionPath" :dur="items.animateMotionDuration" :fill="items.animateMotionFill" />
			</text>
		</g>
		<path
			v-for="(item, index) in items.paths" 
			:key="index"
			:stroke-linecap="item.strokeLinecap" 
			:stroke-linejoin="item.strokeLinejoin" 
			:stroke-width="item.strokeWidth" 
			:d="item.d"
			/>
		<component
			v-for="(item, index) in items.data" 
			:key="index"
			:width="content[item].width"
        	:height="content[item].height"
			:r="content[item].r"
			:points="content[item].points"
			:stroke-width="content[item].strokeWidth"
			:rx="content[item].rx"
			:ry="content[item].ry"
			:cx="content[item].cx"
			:cy="content[item].cy"
			:fill="content[item].fill"
			:filter="content[item].filter"
			:is="content[item].tag">
			<component 
				v-for="(animation, animationIndex) in content[item].animations" 
				:key="animationIndex"
				:is="content[animation].tag"
				:attributeName="content[animation].attributeName" 
				:attributeType="content[animation].attributeType" 
				:begin="content[animation].begin" 
				:dur="content[animation].dur" 
				:fill="content[animation].fill" 
				:from="content[animation].from" 
				:repeatCount="content[animation].repeatCount"
				:values="content[animation].values"
				:to="content[animation].to" />
		</component>
		<circle v-if="!items.data" cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
		Sorry, your browser does not support inline SVG.
    </svg>
	<span v-else @click="clickHandler">
		<svg 
			v-for="(item, index) in items.data"
			v-show="items.toggle && index == currentIndex"
			:key="index"
			:class="[classes, enabledClasses]"
			xmlns="http://www.w3.org/2000/svg"
			xmlns:xlink="http://www.w3.org/1999/xlink"
			:width="content[item].width"
			:height="content[item].height"
			:viewBox="content[item].viewBox"
			:fill="content[item].fill"
			:stroke="content[item].stroke"
			:preserveAspectRatio="content[item].preserveAspectRatio"
			:aria-labelledby="content[item].name"
			:role="content[item].presentation"
			v-html="content[item] ? content[item].path : ''">
		</svg>
	</span>
</template>

<script>
	export default {
		props: {
			record: {
                type: String,
                default: null
            },
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
				currentIndex: 0
			};
		},
		methods: {
            mouseoverEvent() {
                if (this.items.mouseoverEvent) {
                    this.$root.$emit(this.items.mouseoverEvent, this.items);
                }
            },
            mouseleaveEvent() {
                if (this.items.mouseleaveEvent) {
                    this.$root.$emit(this.items.mouseleaveEvent, this.items);
                }
            },
            clickHandler() {
                if (this.items.toggle && this.items.data) {
					this.$set(this, 'currentIndex', (this.currentIndex + 1) % this.items.data.length);
				}
				if (this.items.target && this.content[this.items.target] && this.items.targetField) {
					if (this.items.toggleTargetField) {
						this.$set(this.content[this.items.target], this.items.targetField, this.content[this.items.target][this.items.targetField] == this.items.toggleState[0] ? this.items.toggleState[1] : this.items.toggleState[0]);
					}	
				}
            }
        },
		computed: {
			classes() {
				return this.items.classes;
			},
			enabledClasses() {
                if (this.items.watch && this.content[this.items.watch].enabled) {
                    return this.items.enabledClasses;
                }
            }
		},
		beforeMount: function() {
			if (this.opts != null) {
                this.items = this.opts;
			}
		},
	};
</script>

