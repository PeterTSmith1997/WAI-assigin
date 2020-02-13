(function () {
    "use strict";

    /**
     * Extend the module 'CourseApp' instantiated in app.js  To add a controller called
     * IndexController (on line 17)
     *
     * The controller is given two parameters, a name as a string and an array.
     * The array lists any injected objects to add and then a function which will be the
     * controller object and can contain properties and methods.
     * $scope is a built in object which refers to the application model and acts
     * as a sort of link between the controller, its data and the application's views.
     * '
     * @link https://docs.angularjs.org/guide/scope
     */
    angular.module('App').
    controller('IndexController',   // controller given two params, a name and an array
        [
            '$scope',               // angular variable as a string
            function ($scope) {
                // add a title property which we can refer to in our view (index.html in this example)
                $scope.title = "Chi Infomation";
            }
        ]
    ).
    controller('Schedule',
            [

                '$scope', // angular variable as a string
                'dataService',
                '$location',
                '$routeParams',
               function ($scope, dataService, $routeParams, $location) {
                    console.log($routeParams);
                var getSchedules = function (day) {
                    dataService.getSchedules(day).then(  // then() is called when the promise is resolve or rejected
                        function(response){
                            console.log(response);
                            $scope.schedules = response.data;
                        },
                        function(err){
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function(notify){
                            console.log(notify);
                        }
                    ); // end of getCourses().then
                };
                   getSchedules($routeParams.day);
                if ($routeParams && $routeParams.day){
                    console.log($routeParams.day);
                    getSchedules("Monday");
                }

                }
            ]).
    controller('PaperSearch',
        [

            '$scope',               // angular variable as a string
            'dataService',
            function ($scope, dataService) {
                var getPapers = function () {
                    dataService.getPapers().then(
                        function(response){
                            console.log(response);

                            $scope.papers = response.data;
                        },
                        function(err){
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function(notify){
                            console.log(notify);
                        }
                    ); // end of getCourses().then
                };

                getPapers();  // call the method just defined

            }
        ]).
        controller('daysController',
        [

            '$scope',               // angular variable as a string
            'dataService',
            '$location',
            function ($scope, dataService, $location) {
                var getDays = function () {
                    dataService.getDays().then(
                        function (response) {
                            console.log(response);

                            $scope.days = response.data;
                        },
                        function (err) {
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function (notify) {
                            console.log(notify);
                        }
                    ); // end of getCourses().then
                };

                var dayInfo = $location.path().substr(1).split('/');
                if (dayInfo.length === 2) {
                    // use the course code from the path and assign to
                    // selectedCourse so if the page is reloaded it's highlighted
                    $scope.selectedDay = {day: dayInfo[1]};
                }
                $scope.selectDay = function($event, day){
                     $scope.selectedDay = day;
                     $location.path('/Schedule/' + day.day);
                };

                getDays();  // call the method just defined
            }
                ])


}());