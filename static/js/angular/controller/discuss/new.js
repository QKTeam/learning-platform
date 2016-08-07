app.controller('discuss.new', ['$scope', '$rootScope', '$http', '$state', '$uibModalInstance', 'courseId', 'fatherId', function($scope, $rootScope, $http, $state, $uibModalInstance, courseId, fatherId){
	$scope.discuss = {
		title: '',
		content: '',
		courseId: courseId,
		fatherId: fatherId
	}
	$scope.add = function () {
		$http.post('/api/discuss/new', $scope.discuss).success(function (response) {
			if(response.code === '0000') {
				$uibModalInstance.close();
			}
			else {
				alert(response.errorMsg);
			}
		});
	}
}]);
