<div class="searchlist">	
		<?php echo isset($_PAGE['search']) ?   $_PAGE['search'] : "" ;?>	
	</div>
	
	<div class="sorttypelist">	
		<?php echo isset($_PAGE['sort']) ?   $_PAGE['sort'] : "" ;?>		
	</div>

	<div class="mainTitle">
		<?php echo isset($_PAGE['title']) ?   $_PAGE['title'] : "" ;?>			
	</div>
		
	<div class="piclist">
		<?php echo isset($_PAGE['items']) ?   $_PAGE['items'] : "" ;?>
	</div>
	
	
</div>

<?php include('megadrawer.html');?>
<?php include('footer.html');?>
<div class="lightBoxBackground">
</div>

</body>
</html>