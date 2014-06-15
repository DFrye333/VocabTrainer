function main(vocabEntries)
{
	$(document).ready(function()
	{
		$('input[name="flipButton"]').click(function(event)
		{
			var selectedIndex = $('select[name="wordList"]').val();
			$('span[id="revealBox"] span').html(JSON.stringify(vocabEntries[selectedIndex].definition));
		});
	});
}