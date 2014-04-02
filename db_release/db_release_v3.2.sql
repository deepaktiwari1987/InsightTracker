-- Insert old responses into replyresponse table.
INSERT INTO replyresponses(insight_id,reply_text)
SELECT id,do_action FROM insights WHERE do_action !='';

-- Update date for response to insight.
UPDATE replyresponses SET date_submitted = '1971-01-01 00:00:00' WHERE user_id IS NULL;
