myApp = angular.module('myApp', ['ngSanitize']);



myApp.controller('myController', function($scope, $http) {
  $http({
    method : "GET",
    url : "/wordpress/wp-json/wp/v2/grand-rounds-lecture?per_page=100"
  }).then(function mySucces(response) {
      $scope.myData = response.data;
    }, function myError(response) {
      $scope.myData = response.statusText;
  });
	


  $scope.sortType     = 'acf.time_and_date'; // set the default sort type
  $scope.sortReverse  = true;  // set the default sort order
  $scope.searchGrandRounds   = '';     // set the default search/filter term


  




 
});