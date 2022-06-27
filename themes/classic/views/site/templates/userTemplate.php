<div v-bind:id="'userWindow_'+id" class="mydiv"  @mousedown="updateZ($event.target)" :style="{'z-index':zindexlocal}">
  <div v-bind:id="'userWindow_'+id+'header'" class="mydivheader">{{ firstName }} {{ lastName }}:свойства {{ zindexlocal }} 
  <button  type="button"  @click="dispose" style="float:right;">x</button>
  </div>
<form><div class="formcontents">
          <h3> <input type="text" v-model="firstName" name="firstName"/> <input type="text" v-model:value="lastName" name="lastName"/></h3>
          <div>Ид пользователя: {{ id }}</div>
          <div>Позиция пользователя: <input type="text" v-model="position"  name="position"/></div>
          <div>ЗП пользователя: <input type="text" v-model="salary" name="salary"/></div>
          <!-- <div><input type="button"  v-on:click="$emit('user-save', $event.target.form)"  value="Сохранить">--><br>
          <button type="button" @click="update">Сохранить</button>
          </div><!-- обязательно дефиз "user-click" -->


 </form>
 </div>
