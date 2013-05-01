function showmenu()
		{
			document.getElementById('menu').style.display = 'block';
			document.getElementById('menu').style.opacity = '1';
			
			document.getElementById('icon').setAttribute("onclick","hidemenu();");
		}
		function hidemenu()
		{
			
			document.getElementById('menu').style.opacity = '0';
			document.getElementById('menu').style.display = 'none';
			document.getElementById('icon').setAttribute("onclick","showmenu();");
		}  