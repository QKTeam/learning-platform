app.controller('course.show', ['$scope', '$rootScope', '$http', '$state', '$timeout', '$uibModal', function($scope, $rootScope, $http, $state, $timeout, $uibModal){
	var fetchCourse = function () {
		$http.post('/api/course/show', {cid: $state.params.cid}).success(function (response) {
			if(response.code === '0000') {
				$scope.course = response.response;
			}
		});
	}
	$timeout(fetchCourse, 0);

	$scope.points = [];
	var fetchPointList = function () {
		$http.post('/api/point/list', {courseId: $state.params.cid}).success(function (response) {
			if(response.code === '0000') {
				for(var i = 0; i < response.response.length; i++) {
					response.response[i].importanceStr = '';
					for(var j = 0; j < response.response[i].importance; j++) {
						response.response[i].importanceStr = response.response[i].importanceStr + '!';
					}
				}
				$scope.points = response.response;
			}
		});
	}
	$timeout(fetchPointList, 0);

	$scope.discusses = [];
	var fetchDiscussList = function () {
		$http.post('/api/discuss/list', {courseId: $state.params.cid}).success(function (response) {
			if(response.code === '0000') {
				$scope.discusses = response.response;
			}
		});
	}
	$timeout(fetchDiscussList, 0);

	$scope.modifyCourse = function () {
		$uibModal.open({
			templateUrl: '/template/course/renew.html',
			controller: 'course.renew',
			resolve: {
				course: [function () {
					return angular.copy($scope.course);
				}]
			}
		}).result.then(function () {
			// console.log('closed');
			$timeout(fetchCourse, 0);
		});
	}

	$scope.addPoint = function () {
		$uibModal.open({
			templateUrl: '/template/point/new.html',
			controller: 'point.new',
			resolve: {
				courseId: [function () {
					return angular.copy($scope.course.cid);
				}]
			}
		});
	}

	$scope.addDiscuss = function (fatherId) {
		$uibModal.open({
			templateUrl: '/template/discuss/new.html',
			controller: 'discuss.new',
			resolve: {
				courseId: [function () {
					return angular.copy($scope.course.cid);
				}],
				fatherId: [function () {
					return fatherId;
				}]
			}
		}).result.then(function () {
			$timeout(fetchDiscussList, 0);
		});
	}
}]);
