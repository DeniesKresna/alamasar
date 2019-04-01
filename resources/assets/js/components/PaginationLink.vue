<template>
	<div>
        <div class="btn-group pull-right">
        	<button type="button" class="btn btn-sm" @click="retPage(1)">&laquo;</button>
        	<button type="button" class="btn btn-sm" v-for="numblink in numbLinks" @click="retPage(numblink)">
        		{{numblink.toString()}}
        	</button>
            <button type="button" class="btn btn-sm" @click="retPage(pakhir)">&raquo;</button>
        </div>
    </div>
</template>
<script>
export default{
	data(){
		return {
			numberLink: [],
			numblink:''
		}
	},
	props: ['pskrg','pakhir'],
	methods:{
		retPage(pg)
		{
			this.$emit('paginate',pg);
		}
	},
	computed:{
		skrg: function()
		{
			return this.pskrg;
		},
		akhir: function()
		{
			return this.pakhir;
		},
		numbLinks: function()
		{
			this.numberLink = [];
			var i = -2;
			var j = 1;
			while(j<=5)
			{
				//console.log(this.pskrg);
				if((i+this.skrg) > 0){
					if((i+this.skrg) <= this.akhir){
						this.numberLink.push(i+this.skrg);
					}
				}
				i++;
				j++;
			}
			return this.numberLink;
		}
	}
}
</script>