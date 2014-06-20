function main(vocabEntries)
{
	$(document).ready(function()
	{
		$('button.wordCard').click(
			function(event)
			{
				// If word card is currently face-down, flip it face-up.
				if ($(this).html() == vocabEntries[$(this).val()].word)
				{
					$(this).transition(
						{
							height:"auto",
							transition:"transform 0.1s",
							transform:"rotateY(360deg)"},
							function(event){ $(this).css('transform', '');
						});

					$(this).html(
						"<b>Word:</b> " + vocabEntries[$(this).val()].word + "<br />" + 
						"<b>Part of Speech:</b> " + vocabEntries[$(this).val()].pos + "<br />" + 
						"<b>Definition:</b> " + vocabEntries[$(this).val()].definition + "<br />");
				}

				// If word card is currently face-up, flip it face-down.
				else
				{
					$(this).transition(
						{
							height:"100px",
							transition:"transform 0.1s",
							transform:"rotateY(360deg)"},
							function(event){ $(this).css('transform', '');
						});

					$(this).html(vocabEntries[$(this).val()].word);
				}
			}
		);
	});
}