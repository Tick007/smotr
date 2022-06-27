var button_type =  {

  name: 'ButtonComponent',
  data () {
    return {
      Id: (function () {
        const one = Math.floor((Math.random() * 1000000) + 1) + ''
        const two = Math.floor((Math.random() * 1000000) + 1) + ''
        const three = Math.floor((Math.random() * 1000000) + 1) + ''
        return 'id' + one + two + three
      })(),

    }
  },
  props: {
    isBtnActive: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    btnClick () {
      console.log("click")
      this.$emit('btnClick')
    }
  },
  watch: {
    // isBtnActive () {
    //   gsap.set('#' + 'btn-' + this.Id, {transformOrigin: '50% 50%'})
    //   if (this.isBtnActive) {
    //     gsap.to('#' + 'btn-' + this.Id, {duration: 0.3, scale: 0.8})
    //   } else {
    //     gsap.to('#' + 'btn-' + this.Id, {duration: 0.3, scale: 1})
    //   }
    // }
  },
  template: `  <div class="btn-container">
    <svg class="background" viewBox="0 0 80 80">
      <filter id="dropshadow" height="130%">
        <feGaussianBlur in="SourceAlpha" stdDeviation="3"/> <!-- stdDeviation is how much to blur -->
        <feOffset dx="0" dy="0" result="offsetblur"/> <!-- how much to offset -->
        <feComponentTransfer>
          <feFuncA type="linear" slope="0.5"/> <!-- slope is the opacity of the shadow -->
        </feComponentTransfer>
        <feMerge>
          <feMergeNode/> <!-- this contains the offset blurred image -->
          <feMergeNode in="SourceGraphic"/> <!-- this contains the element that the filter is applied to -->
        </feMerge>
      </filter>
      <filter id="blur">
        <feGaussianBlur stdDeviation="2"/>
      </filter>
      <defs>
        <linearGradient id="Gradient-1" x1="0" y1="0" x2="100%" y2="100%">
          <stop offset="20%"  stop-color="#f2f2f2"/>
          <stop offset="70%" stop-color="#404040"/>
        </linearGradient>
        <linearGradient id="Gradient-2" x1="0" y1="0" x2="100%" y2="100%">
          <stop offset="20%"  stop-color="#787878"/>
          <stop offset="70%" stop-color="#f2f2f2"/>
        </linearGradient>
        <linearGradient id="Gradient-3" x1="50%" y1="0" x2="50%" y2="100%">
          <stop offset="5%"  stop-color="#fdfefb" stop-opacity="0.6"/>
          <stop offset="70%" stop-color="#fdfefb" stop-opacity="0.1"/>
        </linearGradient>
      </defs>
      <circle class="background-circle-1" r="40px" cx="40" cy="40"></circle>
      <circle class="background-circle-2" r="37px" cx="40" cy="40"></circle>
      <g :id="'btn-' + Id" class="button-circle" @click="btnClick">
        <circle r="35px" cx="40" cy="40" ></circle>
        <path style="stroke-linejoin: round; fill: rgb(255, 255, 255); stroke: rgb(95, 119, 208); stroke-width: 0.5px;" d="M 38 18 L 42 18 L 42 42 L 38 42 L 38 18 Z M 33 30 C 33 30 28 34 28 40 C 28 46 31.394 52 40 52 C 48.606 52 52 46 52 40 C 52 34 47 30 47 30 L 50 27 C 50 27 56 31 56 40 C 56 52 46 56 40 56 C 34 56 24 52 24 40 C 24 31 30 27 30 27 L 33 30 Z"></path>
        <path class="flare" d="M 40 13 C 58 13 65 23 66 27 C 67 34 60 36 60 36 C 55 37 46.741 33 40 33 C 33.259 33 25 37 20 36 C 20 36 13 35 14 27 C 15 23 22 13 40 13 Z"></path>
      </g>
    </svg>
  </div>`,
}
