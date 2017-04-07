      	  <?php if (isset($_GET['con'])) : ?>
    
                                   <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                         <a href="" class="alert-link">Bienvenue, <?= ucfirst(strtolower($_SESSION['name'])); ?> <?= strtolower($_SESSION['pname']); ?></a>
                                  </div>
                                  
                                    <?php endif;?>
   <?php 
					if(@$msge!='') echo "<div class='img-responsive row img-circle'> <div class='col-md-12'>".$msge."</div></div>";					
					?>
<div class="row">
			    <div class="accueil">
					</div>
                      </div>
                       