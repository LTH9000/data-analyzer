// Opens a file
var openFile = function(event) {
  var input = event.target;

  // use PapaParse for handing the csv file
  var results = Papa.parse(input.files[0], {
  	complete: function(results) {
  		loadData(results);
  	}
  });
};

var loadListener = function(){
  document.getElementById('files').addEventListener('change', openFile, false);
}
