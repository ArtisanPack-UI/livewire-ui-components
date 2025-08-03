/**
 * ArtisanPack UI TinyMCE Editor Integration with Alpine.js
 * Handles initialization and management of TinyMCE editors with Livewire
 */

// Define TinyMCE Alpine data BEFORE Alpine initializes
document.addEventListener("alpine:init", () => {
    Alpine.data("tinymceEditor", (editorId, wireModel) => ({
        editor: null,
        editorId: editorId,
        wireModel: wireModel,

        initEditor() {
            console.log("🚀 [Alpine] Initializing TinyMCE for:", this.editorId);

            // Wait for TinyMCE to be available
            this.waitForTinyMCE().then(() => {
                this.setupEditor();
            });
        },

        waitForTinyMCE() {
            return new Promise((resolve) => {
                if (typeof tinymce !== "undefined") {
                    console.log("✅ [Alpine] TinyMCE already available, version:", tinymce.majorVersion);
                    resolve();
                    return;
                }

                console.log("⏳ [Alpine] Waiting for TinyMCE to load...");
                const checkInterval = setInterval(() => {
                    if (typeof tinymce !== "undefined") {
                        console.log("✅ [Alpine] TinyMCE loaded, version:", tinymce.majorVersion);
                        clearInterval(checkInterval);
                        resolve();
                    }
                }, 100);

                // Timeout after 10 seconds
                setTimeout(() => {
                    clearInterval(checkInterval);
                    console.error("❌ [Alpine] TinyMCE failed to load within 10 seconds");
                    resolve(); // Resolve anyway to prevent hanging
                }, 10000);
            });
        },

        setupEditor() {
            const textarea = document.getElementById(this.editorId);
            if (!textarea) {
                console.error("❌ [Alpine] Textarea not found:", this.editorId);
                return;
            }

            // Check if editor already exists
            if (tinymce.get(this.editorId)) {
                console.log("ℹ️ [Alpine] Editor already exists, destroying first");
                tinymce.get(this.editorId).destroy();
            }

            const isDark = document.documentElement.classList.contains("dark") ||
                document.body.classList.contains("dark") ||
                window.matchMedia("(prefers-color-scheme: dark)").matches;

            console.log("🔧 [Alpine] Setting up TinyMCE editor:", this.editorId);

            tinymce.init({
                target: textarea,
                license_key: "gpl",
                skin: isDark ? "oxide-dark" : "oxide",
                content_css: isDark ? "dark" : "default",
                plugins: "anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount",
                toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat",
                menubar: false,
                branding: false,
                height: 300,
                content_style: isDark ?
                    "body { font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif; font-size: 14px; background-color: #1f2937; color: #f9fafb; }" :
                    "body { font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif; font-size: 14px; }",

                setup: (editor) => {
                    console.log("🔧 [Alpine] TinyMCE setup callback:", this.editorId);
                    this.editor = editor;

                    let isUpdating = false;

                    editor.on("change keyup paste input", () => {
                        if (this.wireModel && !isUpdating) {
                            clearTimeout(editor.updateTimeout);
                            editor.updateTimeout = setTimeout(() => {
                                const component = this.$wire || window.Livewire.find(textarea.closest("[wire\\:id]")?.getAttribute("wire:id"));
                                if (component) {
                                    isUpdating = true;
                                    console.log("📝 [Alpine] Updating Livewire model:", this.wireModel);
                                    component.set(this.wireModel, editor.getContent(), false);
                                    setTimeout(() => {
                                        isUpdating = false;
                                    }, 100);
                                }
                            }, 300);
                        }
                    });

                    editor.on("init", () => {
                        console.log("✅ [Alpine] TinyMCE initialized successfully:", this.editorId);
                        textarea.style.visibility = "visible";

                        // Set initial content
                        const initialContent = textarea.value;
                        if (initialContent) {
                            editor.setContent(initialContent);
                        }
                    });
                }
            }).then((editors) => {
                console.log("✅ [Alpine] TinyMCE init completed for:", this.editorId);
            }).catch((error) => {
                console.error("❌ [Alpine] TinyMCE init failed:", error);
            });
        },

        destroy() {
            if (this.editor) {
                console.log("🗑️ [Alpine] Destroying TinyMCE editor:", this.editorId);
                this.editor.destroy();
                this.editor = null;
            }
        }
    }));
});

// Global cleanup function for Livewire
window.cleanupTinyMCEEditors = function() {
    if (typeof tinymce === "undefined") return;

    console.log("🧹 [ArtisanPack] Global TinyMCE cleanup");
    const editors = tinymce.get();
    if (editors && editors.length > 0) {
        editors.forEach(editor => {
            try {
                if (!document.contains(editor.getElement())) {
                    console.log("🗑️ [ArtisanPack] Destroying orphaned editor:", editor.id);
                    editor.destroy();
                }
            } catch (e) {
                console.warn("⚠️ [ArtisanPack] Error during cleanup:", e);
            }
        });
    }
};

// Cleanup on Livewire updates
document.addEventListener("livewire:updated", () => {
    setTimeout(() => {
        window.cleanupTinyMCEEditors();
    }, 100);
});

console.log("🚀 [ArtisanPack] TinyMCE Alpine.js integration loaded");
