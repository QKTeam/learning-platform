app.controller('course.search', ['$scope', '$rootScope', '$http', '$state', '$uibModal', '$timeout', function($scope, $rootScope, $http, $state, $uibModal, $timeout){
	var searchKey = {
		ownerId: $state.params.ownerId,
		name: $state.params.name
	}
	$scope.courses = [];
	var fetchCourseList = function () {
		$http.post('/api/course/list', searchKey).success(function (response) {
			if(response.code === '0000') {
				$scope.courses = response.response;
			}
		});
	}
	$timeout(fetchCourseList, 0);
	$scope.refresh = function () {
		$timeout(fetchCourseList, 0);
	}

	$scope.clearSearchKey = function (key) {
		if(key == 'ownerId') {
			searchKey.ownerId = 0;
		}
		else {
			searchKey.name = '';
		}
		$state.go('course.search', searchKey);
	}

	$scope.newCourse = function () {
		$uibModal.open({
			templateUrl: '/template/course/new.html',
			controller: 'course.new'
		});
	}
}]);
