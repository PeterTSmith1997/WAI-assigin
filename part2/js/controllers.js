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
               function ($scope, dataService) {
                var getSchedules = function () {
                    dataService.getSchedules().then(  // then() is called when the promise is resolve or rejected
                        function(response){

                            $scope.schedule = response.data;
                        },
                        function(err){
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function(notify){
                            console.log(notify);
                        }
                    ); // end of getCourses().then
                };

                getSchedules();  // call the method just defined

                }
            ]).
    controller('PaperSearch',
        [

            '$scope',               // angular variable as a string
            function ($scope) {
                $scope.papers =[
                    {
                        title: "hello"
                    }
                ]

            }
        ])


}());