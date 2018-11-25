//
//	class for html controls
//
function JsLoad() 
{
	var scriptIndex = 0,
		loadTimeout = 0,
		self = this,
		arrScripts = null;

	// functions to load javascripts after html is loaded
	
	// set function to load script into an element
	this.TriggerLoadScriptElement = function() { loadTimeout = setTimeout(function() { self.LoadScriptElement() }, 10); }

	// load scripts one by one, so jquery will be available for scripts that will use it
	this.LoadScriptElement = function()
	{
		clearTimeout(loadTimeout);
		
		if (scriptIndex >= arrScripts.length) // if were all scripts loaded, exit function
			return;
			
		element = document.createElement("script");
		element.src = arrScripts[scriptIndex];
		
		if (document.all) // if IE, use this method, since IE doesn't have an element.onload function
		{
			self.ExecuteOnLoad(element, function() {
				loadTimeout = setTimeout(function() { self.LoadScriptElement() }, 10);
			});
		}
		else
		{
			// when element was loaded, load next script
			element.onload = function() {
				loadTimeout = setTimeout(function() { self.LoadScriptElement() }, 10);
			};
		}
		
		// append child to footerJS
		document.getElementById('footerJS').appendChild(element);
		scriptIndex++;
	}
	
	this.ExecuteOnLoad = function(node, func) 
	{
	   // This function will check, every tenth of a second, to see if 
	   // our element is a part of the DOM tree - as soon as we know 
	   // that it is, we execute the provided function.
	   if(isInDOMTree(node)) { func(); } 
	   else { setTimeout(function() { self.ExecuteOnLoad(node, func); }, 10); }
	}


	// set function to load script into an element
	this.StartLoadScripts = function(scripts, siteUrl)
	{
		// create an array from the scripts and add full path if necessary
		arrScripts = getLoadScriptsArray(scripts, siteUrl);
		
		// Check for browser support of event handling capability
		if (window.addEventListener)
			window.addEventListener("load", function() { self.TriggerLoadScriptElement() }, false);
		else if (window.attachEvent)
			window.attachEvent("onload", function() { self.TriggerLoadScriptElement() });
		else 
			window.onload = function() { TriggerLoadScriptElement() };
	}
	
	function getLoadScriptsArray(scripts, siteUrl)
	{
		var arrScripts = scripts.split('|');
		for (var scriptIndex = 0; scriptIndex < arrScripts.length; scriptIndex++)
		{
			if (arrScripts[scriptIndex].indexOf('http') != 0 && arrScripts[scriptIndex].indexOf('//connect') != 0)
				arrScripts[scriptIndex] = siteUrl + arrScripts[scriptIndex];
		}
		return arrScripts;
	}
	
	function isInDOMTree(node) 
	{
	   // If the farthest-back ancestor of our node has a "body"
	   // property (that node would be the document itself), 
	   // we assume it is in the page's DOM tree.
	   return !!(findUltimateAncestor(node).body);
	}
	
	function findUltimateAncestor(node) 
	{
	   // Walk up the DOM tree until we are at the top (parentNode 
	   // will return null at that point).
	   // NOTE: this will return the same node that was passed in 
	   // if it has no ancestors.
	   var ancestor = node;
	   while(ancestor.parentNode) 
	   {
		  ancestor = ancestor.parentNode;
	   }
	   return ancestor;
	}
};

// once loaded, start to load scripts
var jsl = new JsLoad();
jsl.StartLoadScripts(SCRIPTS, SCRIPTS_URL);