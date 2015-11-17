/* Získa dáta zo servera.
*	@$successListener - funkcia, čo má robiť aj bola požiadavka úspešná
*	@$errorListener - funkcia, čo má robiť aj bola požiadavka neúspešná
*/
function GetDataFromServer(successListener, errorListener){
	
	getDataFromServer(successListener, errorListener);
	
	function getDataFromServer(successListener, errorListener){
		jQuery.ajax({
			url: 'data.json',
			contentType: "application/json",
			success: function(data, textStatus, jqXHR){
				var i,j;
				for(i = 0; i < data.length; i++){
					for(j = 0; j < data[0].length; j++){
						data[i][j] = parseFloat(data[i][j]);
					}
				}
			
				successListener(data, textStatus, jqXHR);
			},
			error: errorListener,
			dataType: 'json'
		});
	}
}