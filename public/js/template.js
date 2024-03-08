function template(htmlOrIdSelector, variables)
{
    if (0 === htmlOrIdSelector.indexOf('#')) {
        htmlOrIdSelector = $(htmlOrIdSelector).html();
    }

    let output = htmlOrIdSelector;
    const regex = /{([a-zA-Z0-9_]+)(\?(.+))?(\#(.+))?}/gm;
    while ((placeholderMatch = regex.exec(htmlOrIdSelector)) !== null) {
        if (placeholderMatch.index === regex.lastIndex) {
            regex.lastIndex++;
        }

        const placeholder = placeholderMatch[0];
        const placeholderName = placeholderMatch[1];
        const isConditionalPlaceholder = undefined !== placeholderMatch[2];
        const isCyclicPlaceholder = undefined !== placeholderMatch[4];
        const value = variables[placeholderName];
        const placeholderRegex = new RegExp(placeholder.replace('?', '\\?'), 'gm');

        if (isConditionalPlaceholder) {
            const conditionalPlaceholderTemplate = placeholderMatch[3];

            if (undefined !== value && null !== value && value.length !== 0) {
                output = output.replaceAll(placeholderRegex, template(conditionalPlaceholderTemplate, variables));
            } else {
                output = output.replaceAll(placeholderRegex, '');
            }
        } else if (isCyclicPlaceholder) {
            const cyclicPlaceholderTemplate = placeholderMatch[5];
            let _cyclicOutput = '';

            _cyclicOutput = value.map((item) => "object" === typeof item ? template(cyclicPlaceholderTemplate, item) : cyclicPlaceholderTemplate.replaceAll(/\%/gm, item)).join('');
            output = output.replaceAll(placeholderRegex, _cyclicOutput);
        } else {
            output = output.replaceAll(placeholderRegex, value || '');
        }
    }

    return output;
}