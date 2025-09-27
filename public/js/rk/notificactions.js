// notifications-modal-style.js (con min/max width)
window.Notification = (function() {

    const typeColors = {
        success: 'text-emerald-500',
        error:   'text-red-500',
        warning: 'text-amber-500',
        info:    'text-blue-500',
    };

    const containerId = 'notification-container';

    function ensureContainer() {
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'fixed inset-0 z-50 pointer-events-none';
            document.body.appendChild(container);
        }
        return container;
    }

    function getPositionContainer(pos) {
        const container = ensureContainer();
        const id = `notif-subcontainer-${pos}`;
        let sub = document.getElementById(id);
        if (!sub) {
            sub = document.createElement('div');
            sub.id = id;
            sub.className = `absolute flex flex-col gap-2 p-4 pointer-events-none ${_positionClass(pos)}`;
            container.appendChild(sub);
        }
        return sub;
    }

    function _positionClass(pos) {
        switch(pos) {
            case 'top-left': return 'top-4 left-4 items-start';
            case 'top-center': return 'top-4 left-1/2 transform -translate-x-1/2 items-center';
            case 'top-right': return 'top-4 right-4 items-end';
            case 'bottom-left': return 'bottom-4 left-4 items-start flex-col-reverse';
            case 'bottom-center': return 'bottom-4 left-1/2 transform -translate-x-1/2 items-center flex-col-reverse';
            case 'bottom-right': return 'bottom-4 right-4 items-end flex-col-reverse';
            case 'center': return 'inset-0 justify-center items-center';
            default: return 'top-4 right-4 items-end';
        }
    }

    class NotificationBuilder {
        constructor(id) {
            this.id = id || `notif-${Date.now()}`;
            this._title = '';
            this._description = '';
            this._html = '';
            this._extra = '';
            this._type = 'info';
            this._duration = 3; 
            this._position = 'top-right';
            this._minWidth = '400px';
            this._maxWidth = '400px';
            this._minHeight = 'auto';
        }

        title(text) { this._title = text; return this; }
        description(text) { this._description = text; return this; }
        html(html) { this._html = html; return this; }
        extra(content) { this._extra = content; return this; }
        type(type) { this._type = type; return this; }
        duration(seconds) { this._duration = seconds; return this; }
        position(pos) { this._position = pos; return this; }
        minWidth(width) { this._minWidth = width; return this; }
        maxWidth(width) { this._maxWidth = width; return this; }
        minHeight(height) { this._minHeight = height; return this; }

        send() {
            const subContainer = getPositionContainer(this._position);

            const wrapper = document.createElement('div');
            wrapper.className = `bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 flex flex-col gap-2 pointer-events-auto`;
            wrapper.style.minWidth = this._minWidth;
            wrapper.style.maxWidth = this._maxWidth;
            wrapper.style.minHeight = this._minHeight;
            wrapper.style.opacity = '0';
            wrapper.style.transform = 'translateY(-10px)';
            wrapper.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

            if (this._html) {
                wrapper.innerHTML = this._html;
            } else {
                const colorClass = typeColors[this._type] || typeColors.info;

                const topRow = document.createElement('div');
                topRow.className = 'flex items-center justify-between w-full';

                const left = document.createElement('div');
                left.className = 'flex items-center gap-2';

                const icon = document.createElement('span');
                icon.className = colorClass;
                icon.innerHTML = {
                    success: '✔️',
                    error: '❌',
                    warning: '⚠️',
                    info: 'ℹ️'
                }[this._type] || 'ℹ️';
                left.appendChild(icon);

                if (this._title) {
                    const t = document.createElement('div');
                    t.className = 'font-semibold text-black dark:text-gray-100';
                    t.innerHTML = this._title;
                    left.appendChild(t);
                }

                topRow.appendChild(left);

                const btn = document.createElement('button');
                btn.innerHTML = '✕';
                btn.className = 'ml-2 text-gray-500 hover:text-gray-800 dark:text-gray-300 dark:hover:text-gray-100';
                btn.onclick = () => _remove(wrapper);
                topRow.appendChild(btn);

                wrapper.appendChild(topRow);

                if (this._description) {
                    const d = document.createElement('div');
                    d.className = 'text-sm text-gray-700 dark:text-gray-300';
                    d.innerHTML = this._description;
                    wrapper.appendChild(d);
                }

                if (this._extra) {
                    const e = document.createElement('div');
                    e.className = 'text-sm text-gray-700 dark:text-gray-300';
                    e.innerHTML = this._extra;
                    wrapper.appendChild(e);
                }
            }

            subContainer.appendChild(wrapper);

            requestAnimationFrame(() => {
                wrapper.style.opacity = '1';
                wrapper.style.transform = 'translateY(0)';
            });

            if (this._duration) {
                setTimeout(() => _remove(wrapper), this._duration * 1000);
            }

            function _remove(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 300);
            }

            return wrapper;
        }
    }

    return {
        make: (id) => new NotificationBuilder(id)
    };
})();
