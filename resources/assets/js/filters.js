const filters =
[
   {
     name: 'role',
     execute: (value) => {
		if(value=="1") return "Administrator";
		else if(value=="2") return "Office";
		else if(value=="3") return "User Biasa";
	}
   },
   {
     name: 'status',
     execute: (value) => {
    if(value=="current") return "Current";
    else if(value=="lulus") return "Lulus";
    else if(value=="dropout") return "Drop Out";
  }
   },
   {
     name: 'kelamin',
     execute: (value) => {
  		if(value=="L") return "Laki-laki";
  		else if(value=="P") return "Perempuan";
	   }
   },
   {
      name: 'toDMYHIS',
      execute: (value) => {
        var hari= ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
        var bulan= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        var timenumb = new Date(value);
        var day = hari[timenumb.getDay()];
        var mon = timenumb.getMonth() + 1;
        var dat = timenumb.getDate();
        var year = timenumb.getFullYear();
        var jam = timenumb.getHours();

        if(dat<10){
          dat = "0" + dat;
        }
        if(mon<10){
          mon = "0" + mon;
        }

        if(jam<10)
        {
          jam = "0"+jam;
        }
        var mnt = timenumb.getMinutes();
        if(mnt<10)
        {
          mnt = "0"+mnt;
        }
        return dat + "-" + mon + "-" + year + " " + jam + ":" + mnt;
      }
   },
   {
      name: 'familyType',
      execute: (value) =>{
        var type=["no","Ayah","Ibu","Kakak Laki-laki","Kakak Perempuan","Adik Laki-laki","Adik Perempuan"];
        return type[value];
      }
   }
]

export default filters

/*
var hari= ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
        var bulan= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        var timenumb = new Date(value);
        var day = hari[timenumb.getDay()];
        var mon = bulan[timenumb.getMonth()];
        var dat = timenumb.getDate();
        var year = timenumb.getFullYear();
        var jam = timenumb.getHours();
        if(jam<10)
        {
          jam = "0"+jam;
        }
        var mnt = timenumb.getMinutes();
        if(mnt<10)
        {
          mnt = "0"+mnt;
        }
        return day + ", " + dat + " " + mon + " " + year + " " + jam + ":" + mnt;
*/