function main(vocabEntries)
{
	$(document).ready(function()
	{
		$('button.wordCard').click(function(event)
		{
			// $(this).removeClass('wordCardFlip');

			if ($(this).html() == vocabEntries[$(this).val()].word)
			{
				$(this).addClass('wordCardFlip');
				$(this).html(vocabEntries[$(this).val()].pos + " : " + vocabEntries[$(this).val()].definition + "<br/>");
			}

			else
			{
				$(this).removeClass('wordCardFlip');
				$(this).html(vocabEntries[$(this).val()].word);
			}
		});
	});
}