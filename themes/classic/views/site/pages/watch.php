<template>
  <div class="clock-container">
    <div id="rotate-background" class="rotate-background"></div>
    <div class="background-clock">
      <div class="time time-back">00:00:00</div>
      <div class="time">{{time}}</div>
    </div>
  </div>
</template>

<script>
import {gsap} from 'gsap'
export default {
  name: 'ClockComponent',
  data () {
    return {
      time: '',
      timer: ''
    }
  },
  methods: {
    updateTime () {
      let cd = new Date()
      this.time = this.zeroPadding(cd.getHours(), 2) + ':' + this.zeroPadding(cd.getMinutes(), 2) + ':' + this.zeroPadding(cd.getSeconds(), 2)
    },
    zeroPadding (num, digit) {
      let zero = ''
      for (let i = 0; i < digit; i++) {
        zero += '0'
      }
      return (zero + num).slice(-digit)
    }
  },
  mounted () {
    this.timer = setInterval(this.updateTime, 1000)
    gsap.to('#rotate-background', {duration: 10, repeat: -1, rotation: 360, ease: 'linear'})
  }
}
</script>

<style scoped>
@font-face {
  font-family: "LCD";
  src: local("LCD"),
  url(../../assets/fonts/9299.ttf) format("truetype");
}

.clock-container {
  overflow: hidden;
  display: grid;
  width: 100%;
  height: 100%;
  font-family: LCD;
  color: #00d6ff;
  border-radius: 7px;
}
.rotate-background {
  z-index: 1;
  grid-row: 1;
  grid-column: 1;
  width: 200%;
  height: 800%;
  justify-self: center;
  align-self: center;
  background:  linear-gradient(to right, rgba(13,57,78,1) 0%, rgba(13,57,78,1) 45%, rgba(0,213,255,0.98) 49%, rgba(0,213,255,1) 51%, rgba(13,57,78,0.98) 55%, rgba(13,57,78,0.96) 100%);
}
.background-clock {
  box-shadow: inset 0px 0px 8px -1px #47d0ee;
  z-index: 2;
  grid-row: 1;
  grid-column: 1;
  display: grid;
  background: #0d394e;
  border-radius: 7px;
  width: 97%;
  height: 95%;
  justify-self: center;
  align-self: center;
}
.time {
  grid-row: 1;
  grid-column: 1;
  justify-self: center;
  align-self: center;
}

.time-back {
  opacity: .1;
}
@media (max-width: 3000px) {
  .clock-container {
    font-size: 65px;
  }
}
@media (max-width: 1920px) {
  .clock-container {
    font-size: 65px;
  }
}
@media (max-width: 1850px) {
  .clock-container {
    font-size: 60px;
  }
}
@media (max-width: 1700px) {
  .clock-container {
    font-size: 55px;
  }
}
@media (max-width: 1600px) {
  .clock-container {
    font-size: 50px;
  }
}
@media (max-width: 1400px) {
  .clock-container {
    font-size: 45px;
  }
}
@media (max-width: 1200px) {
  .clock-container {
    font-size: 40px;
  }
}
@media (max-width: 1000px) {
  .clock-container {
    font-size: 35px;
  }
}
</style>