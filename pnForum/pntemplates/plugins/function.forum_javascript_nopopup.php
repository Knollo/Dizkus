<?php
// ----------------------------------------------------------------------
// Purpose of file: display smilies and bbcodes in forum
// ----------------------------------------------------------------------

/**
 * forum_javascript 
 * adds some javascript needed for smilies and bbcode
 *
 */
function smarty_function_forum_javascript_nopopup($params, &$smarty) 
{
    extract($params); 
	unset($params);

    $out = "<script type=\"text/javascript\">\n";
    $out.= "function x () {\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "function DoSize (fontsize) {\n";
    $out.= "var revisedMessage;\n";
    $out.= "var currentMessage = document.post.message.value;\n";
    $out.= "var sizeBBCode = \"[size=\"+fontsize+\"][/size]\";\n";
    $out.= "revisedMessage = currentMessage+sizeBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "function DoColor (fontcolor) {\n";
    $out.= "var revisedMessage;\n";
    $out.= "var currentMessage = document.post.message.value;\n";
    $out.= "var colorBBCode = \"[color=\"+fontcolor+\"][/color]\";\n";
    $out.= "revisedMessage = currentMessage+colorBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "function DoSmilie(SmilieCode) {\n";
    $out.= "\n";
    $out.= "var SmilieCode;\n";
    $out.= "var revisedMessage;\n";
    $out.= "var currentMessage = document.post.message.value;\n";
    $out.= "revisedMessage = currentMessage+SmilieCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "function DoPrompt(action) {\n";
    $out.= "var revisedMessage;\n";
    $out.= "var currentMessage = document.post.message.value;\n";
    $out.= "\n";
    $out.= "if (action == \"url\") {\n";
    $out.= "var urlBBCode = \"[URL=".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_URL)."]".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_PAGE_TITLE)."[/URL]\";\n";
    $out.= "revisedMessage = currentMessage+urlBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"email\") {\n";
    $out.= "var emailBBCode = \"[EMAIL]".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_EMAIL_ADDRESS)."[/EMAIL]\";\n";
    $out.= "revisedMessage = currentMessage+emailBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"bold\") {\n";
    $out.= "var boldBBCode = \"[B]".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_TEXT_BOLD)."[/B]\";\n";
    $out.= "revisedMessage = currentMessage+boldBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"italic\") {\n";
    $out.= "var italBBCode = \"[I]".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_TEXT_ITALIC)."[/I]\";\n";
    $out.= "revisedMessage = currentMessage+italBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"underline\") {\n";
    $out.= "var ulBBCode = \"[u]".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_TEXT_UNDERLINE)."[/u]\";\n";
    $out.= "revisedMessage = currentMessage+ulBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"image\") {\n";
    $out.= "var imageBBCode = \"[IMG]".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_WEBIMAGE_URL)."[/IMG]\";\n";
    $out.= "revisedMessage = currentMessage+imageBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"quote\") {\n";
    $out.= "var quoteBBCode = \"[QUOTE][/QUOTE]\";\n";
    $out.= "revisedMessage = currentMessage+quoteBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"code\") {\n";
    $out.= "var codeBBCode = \"[CODE][/CODE]\";\n";
    $out.= "revisedMessage = currentMessage+codeBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"listopen\") {\n";
    $out.= "var liststartBBCode = \"[LIST]\";\n";
    $out.= "revisedMessage = currentMessage+liststartBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"listclose\") {\n";
    $out.= "var listendBBCode = \"[/LIST]\";\n";
    $out.= "revisedMessage = currentMessage+listendBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "if (action == \"listitem\") {\n";
    $out.= "var itemBBCode = \"[*]".pnVarPrepForDisplay(_PNFORUM_BBCODE_ENTER_LIST_ITEM)."\";\n";
    $out.= "revisedMessage = currentMessage+itemBBCode;\n";
    $out.= "document.post.message.value=revisedMessage;\n";
    $out.= "document.post.message.focus();\n";
    $out.= "return;\n";
    $out.= "}\n";
    $out.= "\n";
    $out.= "}\n";
    $out.= "</script>\n";
    $out.= "<noscript></noscript>";
    return $out;

}
?>