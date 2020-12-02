<template>
    <svg :class="classes" 
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
		<component
			v-for="(item, index) in items.data" 
			:key="index"
			:width="content[item].width"
        	:height="content[item].height"
			:r="content[item].r"
			:points="content[item].points"
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
    </svg>
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
		beforeMount: function() {
			if (this.opts != null) {
                this.items = this.opts;
			}
		},
	};
</script>
