import hljs from 'highlight.js/lib/core';
import javascript from 'highlight.js/lib/languages/javascript';
import typescript from 'highlight.js/lib/languages/typescript';
import php from 'highlight.js/lib/languages/php';
import css from 'highlight.js/lib/languages/css';
import scss from 'highlight.js/lib/languages/scss';
import xml from 'highlight.js/lib/languages/xml';

hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('typescript', typescript);
hljs.registerLanguage('php', php);
hljs.registerLanguage('css', css);
hljs.registerLanguage('scss', scss);
hljs.registerLanguage('xml', xml);

const codeBlocks = document.querySelectorAll('.wp-block-code');

enum LanguagesRef {
  js = 'javascript',
  javascript = 'javascript',
  ts = 'typescript',
  typescript = 'typescript',
  php = 'php',
  css = 'css',
  scss = 'scss',
  xml = 'xml',
  html = 'xml',
}

enum LanguagesNiceName {
  javascript = 'Javascript',
  typescript = 'Typescript',
  php = 'PHP',
  css = 'CSS',
  scss = 'SCSS',
  xml = 'XML',
  html = 'HTML',
}

codeBlocks.forEach((codeBlock) => {
  const langClass = Object.values(codeBlock.classList)
    .filter((item) => item.startsWith('lang-'))
    .map((item) => item.replace('lang-', ''))[0] as LanguagesRef;

  const contentValue = codeBlock.querySelector('code')?.innerText || '';
  const codeElement = codeBlock.querySelector('code') as HTMLElement;

  if (codeElement !== null) {
    codeElement.innerHTML = '';
  }

  const language = LanguagesRef[langClass || 'xml'] as LanguagesRef;

  const { value } = hljs.highlight(contentValue, {
    language,
  });

  if (codeElement !== null) {
    codeElement.innerHTML = value;
  }

  const languageNameElement = document.createElement('div');
  languageNameElement.className = 'language-name';
  languageNameElement.innerHTML = LanguagesNiceName[language];

  codeBlock.replaceChild(languageNameElement, codeElement);

  const codeWrapper = document.createElement('div');
  codeWrapper.className = 'code-wrapper';

  codeWrapper.append(codeElement);
  codeBlock.append(codeWrapper);
});
