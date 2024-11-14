// PageLoader.js

const loadedResources = new Set();

function loadCSS(href) {
    if (loadedResources.has(href)) return;
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
    loadedResources.add(href);
}

function loadScript(src) {
    return new Promise((resolve, reject) => {
        if (loadedResources.has(src)) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = src;
        script.onload = () => {
            loadedResources.add(src);
            resolve();
        };
        script.onerror = () => {
            console.error(`Failed to load script: ${src}`);
            reject(new Error(`Failed to load script: ${src}`));
        };
        document.head.appendChild(script);
    });
}


async function executeInlineScripts(container) {
    const inlineScripts = container.querySelectorAll('script:not([src])');
    for (const script of inlineScripts) {
        const newScript = document.createElement('script');
        newScript.textContent = script.textContent;
        document.head.appendChild(newScript).parentNode.removeChild(newScript);
    }
}

export default class PageLoader {
    static async loadPage(pageUrl, targetElementId) {
        const targetElement = document.getElementById(targetElementId);

        if (!targetElement) {
            console.error(`Target element with ID "${targetElementId}" not found.`);
            return;
        }

        try {
            const response = await fetch(pageUrl);
            if (!response.ok) throw new Error(`Failed to load page: ${pageUrl}`);

            const htmlContent = await response.text();
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = htmlContent;

            // Extract and load external resources
            const styles = tempDiv.querySelectorAll('link[rel="stylesheet"]');
            const scripts = tempDiv.querySelectorAll('script[src]');

            styles.forEach(link => loadCSS(link.href));
            for (const script of scripts) {
                await loadScript(script.src); // Ensure scripts load in sequence
            }

            // Insert the remaining HTML content into the target element
            targetElement.innerHTML = tempDiv.innerHTML;

            // Execute inline scripts
            await executeInlineScripts(tempDiv);
        } catch (error) {
            targetElement.innerHTML = `<p>Error loading page. Please try again later.</p>`;
            console.error(error);
        }
    }
}
