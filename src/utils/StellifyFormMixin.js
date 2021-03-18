import config from '../utils/config'
import { getValueByPath } from './helpers'

export default {
  inject: {
    $field: { name: "$field", default: false },
    $elementRef: { name: "$elementRef", default: false },
  },
  props: {
    autocomplete: String,
    /** Same as native maxlength, plus character counter */
    maxlength: [Number, String],
    /** Enable html 5 native validation */
    useHtml5Validation: {
      type: Boolean,
      default: () => {
        return getValueByPath(config, "useHtml5Validation", true);
      },
    },
    /**
     * The message which is shown when a validation error occurs
     */
    validationMessage: String,
  },
  data() {
    return {
      isValid: true,
      isFocused: false
    };
  },
  methods: {
    /**
     * Focus method that work dynamically depending on the component.
     */
    focus() {
      const el = this.getElement();
      if (el === undefined) return;

      this.$nextTick(() => {
        if (el) el.focus();
      });
    },

    onBlur($event) {
      this.isFocused = false;
      this.$emit("blur", $event);
      this.checkHtml5Validity();
    },

    onFocus($event) {
      this.isFocused = true;
      this.$emit("focus", $event);
    },

    getElement() {
      return this.$refs['input'];
    },

    setInvalid() {
      const message = this.validationMessage || this.getElement().validationMessage;
      this.setValidity(message);
    },

    setValidity(message) {
      this.$nextTick(() => {
        this.newMessage = message;
      });
    },

    /**
     * Check HTML5 validation, set isValid property.
     * If validation fail, send 'danger' type,
     * and error message to parent if it's a Field.
     */
    checkHtml5Validity() {
      if (!this.useHtml5Validation) return;

      const el = this.getElement();
      if (el === undefined) return;

      if (!el.checkValidity()) {
        this.setInvalid();
        this.isValid = false;
      } else {
        this.setValidity(null);
        this.isValid = true;
      }

      return this.isValid;
    },
  },
};