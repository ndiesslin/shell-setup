<?php
/*
Template Name: GrandRound Listings Test
*/

get_header();

?>

<?php get_template_part('template-title');?>

<!-- DIVI CATCH -->

<div id="main-content" ng-app="myApp" >



	
			
				<article id="post-18097" class="post-18097 page type-page status-publish hentry" ng-controller="myController">

				



<div class="entry-content">
					<div class="et_pb_section  et_pb_section_0 et_section_regular">

					<!-- Angular Loop -->

					<div class=" et_pb_row et_pb_row_0" >

										  <form>
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-addon"><i class="fa fa-search"></i></div>
        <input type="text" class="form-control" placeholder=" Search Grand Rounds" ng-model="searchGrandRounds">
      </div>      
    </div>
  </form>
				
				<div class="et_pb_column et_pb_column_1_3  et_pb_column_2">
				
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2">
				
<p><strong><a href="#" ng-click="sortType = 'acf.time_and_date'; sortReverse = !sortReverse">Date</a></strong> <span ng-show="sortType == 'acf.time_and_date' && !sortReverse" class="fa fa-caret-down"></span>
        <span ng-show="sortType == 'acf.time_and_date' && sortReverse" class="fa fa-caret-up"></span></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_3">
				
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_3">
				
<p><strong><a href="#" ng-click="sortType = 'title.rendered'; sortReverse = !sortReverse">Presentation</a></strong>  <span ng-show="sortType == 'title.rendered' && !sortReverse" class="fa fa-caret-down"></span>
        <span ng-show="sortType == 'title.rendered' && sortReverse" class="fa fa-caret-up"></span></p> 

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_4">
				
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_4">
				
<p><strong><a href="#" ng-click="sortType = 'acf.lecturer'; sortReverse = !sortReverse">Lecturer</a></strong> <span ng-show="sortType == 'acf.lecturer' && !sortReverse" class="fa fa-caret-down"></span>
        <span ng-show="sortType == 'acf.lecturer' && sortReverse" class="fa fa-caret-up"></span></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
					
			</div> <!-- .et_pb_row -->



			<div class=" et_pb_row et_pb_row_{{$index + 1}}" ng-repeat="data in myData | orderBy:sortType:sortReverse | filter:searchGrandRounds">
				
				<span if-show="data.acf.status == 'publish'"><hr />
				<div class="et_pb_column et_pb_column_1_3  et_pb_column_6">
				
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_6">
				
<p>{{data.acf.time_and_date | date}}</p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_7">
				
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_7">
				
<p><a href="{{data.link}}">{{data.title.rendered}}</a></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_8">
				
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_8">
				
<div ng-bind-html="data.acf.lecturer"></div>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->
					


</span>
					<!-- Angular Loop -->

		
					
	
				
			</div> <!-- .et_pb_section -->


					<!-- Angular Loop -->

		
					{{myData}} Keep Looking {{myData}}
	
				
			</div> <!-- .et_pb_section -->
						
		</div> <!-- .entry-content -->

				
				</article> <!-- .et_pb_post -->

			
			
</div> <!-- #main-content -->

<!-- DIVI CATCH -->


<?php get_footer(); ?>
