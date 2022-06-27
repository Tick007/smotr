<script>
$(document).ready(function(){

	for (var i = 1, j = 1; i <= 6; i++, j++) {
	   // setTimeout(f.bind(i, i), j * 1000);
		setTimeout(f(i),j*5000);
	}
});


function f(k) {
    console.log(this+" "+k);
}

</script>