<svg id="svg1" width="600px" height="300px" viewBox="0 0 600 300" style="enable-background:new">


<line x1="225" y1="140" x2="225" y2="290" style="stroke:#933135;stroke-width:1;"  stroke-dasharray= "150" stroke-dashoffset="150" fill="none">
<animate id="p2" attributeName="stroke-dashoffset" begin="indefinite" values="150; 0" dur="0.5s" repeatCount="1"  fill="freeze"  calcMode="linear"/>
	</path>
</line>
 <path id="path1" fill="none" stroke ="black" stroke-dasharray= "600" stroke-dashoffset="0"  d="M113.5 57.7l-8.5-11.4 -2.5-14c-0.8-4.3-4.3-7.7-8.6-8.3L79.8 22.1l-11.7-8.1c-2.9-2-6.6-2.4-9.9-1 -0.7 0.3-1.4 0.7-2 1.2l-11.4 8.5 -14 2.5c-0.8 0.1-1.5 0.4-2.3 0.7 -3.2 1.4-5.5 4.4-6 7.9L20.5 48l-8.1 11.7c-2.5 3.6-2.4 8.4 0.2 12l8.5 11.4 2.5 14c0.8 4.3 4.3 7.7 8.6 8.3l14.1 2 11.7 8.1c2.9 2 6.7 2.4 9.9 1 0.7-0.3 1.4-0.7 2-1.2l11.4-8.5 14-2.5c0.8-0.1 1.5-0.4 2.3-0.7 3.2-1.4 5.5-4.4 6-7.9l2-14.1 8.1-11.7C116.3 66 116.2 61.2 113.5 57.7zM63.1 102c-20.6 0-37.4-16.8-37.4-37.4s16.8-37.4 37.4-37.4 37.4 16.8 37.4 37.4S83.7 102 63.1 102zM63.1 30.8c-18.7 0-33.8 15.2-33.8 33.8 0 18.7 15.2 33.8 33.8 33.8 18.7 0 33.8-15.2 33.8-33.8C96.9 46 81.7 30.8 63.1 30.8zM63.1 40.1c3.5 0 6.9 0.8 10.2 2.2 0 0.3 0 15.3 0 16.4 -0.8-0.8-18.1-16.4-18.8-17C57.3 40.6 60.1 40.1 63.1 40.1zM38.5 64.7c0-9.3 5.1-17.7 13.4-21.9 0.3 0.3 8.9 8.1 9.3 8.5 -0.4 0.4-21.2 19.2-21.8 19.8C38.8 69 38.5 66.8 38.5 64.7zM50.4 85.7c-4.6-2.8-8.2-7-10.2-11.9 0.3-0.3 9.4-8.5 10.2-9.2C50.4 65.7 50.4 84.8 50.4 85.7zM65.3 89.2h-4.5c-2.7-0.2-5.3-0.9-7.8-2.1 0-0.4 0-11.2 0-11.7 0.5 0 31.3 0 32.2 0C81.4 83.2 73.8 88.4 65.3 89.2zM86.3 72.8c-0.4 0-10 0-10.5 0 0-0.5 0-28.1 0-29.1 7.3 4.5 11.9 12.4 11.9 21C87.7 67.4 87.2 70.2 86.3 72.8z" >
	<animate id="p1" attributeName="stroke-dashoffset" begin="svg1.click" values="600; 0" dur="10s" repeatCount="1"  fill="freeze"  calcMode="linear"/>
	</path>
    <text  x="98" y="17" font-size="11" font-family="Ariel" text-anchor="middle" 
        fill="green" stroke="grey" stroke-width="0.5px" >Click me</text>
        
<line x1="225" y1="290" x2="10" y2="290" style="stroke:#933135;stroke-width:1"></line>


<path id="path2" fill="none"  stroke ="black" stroke-dasharray= "415" stroke-dashoffset="415"  d="M230 145 l0, 150 l-215, 0, l0,-50">
	<animate id="p3" attributeName="stroke-dashoffset" begin="indefinite" values="415; 0" dur="1s" repeatCount="1"  fill="freeze"  calcMode="linear"/>
</path>

</svg>

<input  type="button" id="as1" value="Anim Start" onclick="StartAnim()"/>
<input  type="button" value="Total"  onclick="TotalLength()"/>

 <script>
         function TotalLength(){
          var path = document.querySelector('#path2');
        var len = Math.round(path.getTotalLength() );
        alert("?????????? ???????? - " + len);
        };



        function StartAnim () {
        	setTimeout(function() {
        		document.getElementById("p3").beginElement();
        		//$('#p2').beginElement();
        	}, 1000);
        	}

        
  </script>