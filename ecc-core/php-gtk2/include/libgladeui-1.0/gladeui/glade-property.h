/* -*- Mode: C; tab-width: 8; indent-tabs-mode: t; c-basic-offset: 8 -*- */
#ifndef __GLADE_PROPERTY_H__
#define __GLADE_PROPERTY_H__

#include <glib-object.h>

G_BEGIN_DECLS

#define GLADE_TYPE_PROPERTY            (glade_property_get_type())
#define GLADE_PROPERTY(obj)            (G_TYPE_CHECK_INSTANCE_CAST ((obj), GLADE_TYPE_PROPERTY, GladeProperty))
#define GLADE_PROPERTY_KLASS(klass)    (G_TYPE_CHECK_CLASS_CAST ((klass), GLADE_TYPE_PROPERTY, GladePropertyKlass))
#define GLADE_IS_PROPERTY(obj)         (G_TYPE_CHECK_INSTANCE_TYPE ((obj), GLADE_TYPE_PROPERTY))
#define GLADE_IS_PROPERTY_KLASS(klass) (G_TYPE_CHECK_CLASS_TYPE ((klass), GLADE_TYPE_PROPERTY))
#define GLADE_PROPERTY_GET_KLASS(o)    (G_TYPE_INSTANCE_GET_CLASS ((o), GLADE_PROPERTY, GladePropertyKlass))

typedef struct _GladePropertyKlass GladePropertyKlass;

/* A GladeProperty is an instance of a GladePropertyClass.
 * There will be one GladePropertyClass for "GtkLabel->label" but one
 * GladeProperty for each GtkLabel in the GladeProject.
 */
struct _GladeProperty
{
	GObject             parent_instance;

	GladePropertyClass *klass;     /* A pointer to the GladeProperty that this
					* setting specifies
					*/
	GladeWidget        *widget;    /* A pointer to the GladeWidget that this
					* GladeProperty is modifying
					*/
	
	GValue             *value;     /* The value of the property
					*/

	gboolean            sensitive; /* Whether this property is sensitive (if the
					* property is "optional" this takes precedence).
					*/
	gchar              *insensitive_tooltip; /* Tooltip to display when in insensitive state
						  * (used to explain why the property is insensitive)
						  */

	gboolean            enabled;   /* Enabled is a flag that is used for GladeProperties
					* that have the optional flag set to let us know
					* if this widget has this GladeSetting enabled or
					* not. (Like default size, it can be specified or
					* unspecified). This flag also sets the state
					* of the property->input state for the loaded
					* widget.
					*/

	gboolean            save_always; /* Used to make a special case exception and always
					  * save this property regardless of what the default
					  * value is (used for some special cases like properties
					  * that are assigned initial values in composite widgets
					  * or derived widget code).
					  */

	/* Used only for translatable strings. */
	gboolean  i18n_translatable;
	gboolean  i18n_has_context;
	gchar    *i18n_comment;
		
	gboolean     syncing;    /* Avoid recursion while synchronizing object with value.
				  */
};


struct _GladePropertyKlass
{
	GObjectClass  parent_class;

	/* Class methods */
	GladeProperty *         (* dup)                   (GladeProperty *, GladeWidget *);
	gboolean                (* equals_value)          (GladeProperty *, const GValue *);
	void                    (* set_value)             (GladeProperty *, const GValue *);
	void                    (* get_value)             (GladeProperty *, GValue *);
	void                    (* get_default)           (GladeProperty *, GValue *);
	void                    (* sync)                  (GladeProperty *);
	void                    (* load)                  (GladeProperty *);
	gboolean                (* write)                 (GladeProperty *, GladeInterface *, GArray *);
	G_CONST_RETURN gchar *  (* get_tooltip)           (GladeProperty *);

	/* Signals */
	void             (* value_changed)         (GladeProperty *, GValue *, GValue *);
	void             (* tooltip_changed)       (GladeProperty *, const gchar *);
};


GType                   glade_property_get_type              (void) G_GNUC_CONST;

GladeProperty          *glade_property_new                   (GladePropertyClass *klass,
							      GladeWidget        *widget,
							      GValue             *value);

GladeProperty          *glade_property_dup                   (GladeProperty      *template_prop,
							      GladeWidget        *widget);

void                    glade_property_reset                 (GladeProperty      *property);

void                    glade_property_original_reset    (GladeProperty      *property);

gboolean                glade_property_default               (GladeProperty      *property);

gboolean                glade_property_original_default  (GladeProperty      *property);

gboolean                glade_property_equals_value          (GladeProperty      *property, 
							      const GValue       *value);

gboolean                glade_property_equals                (GladeProperty      *property, 
							      ...);

void                    glade_property_set_value             (GladeProperty      *property, 
							      const GValue       *value);

void                    glade_property_set_va_list           (GladeProperty      *property,
							      va_list             vl);

void                    glade_property_set                   (GladeProperty      *property,
							      ...);

void                    glade_property_get_value             (GladeProperty      *property, 
							      GValue             *value);

void                    glade_property_get_default           (GladeProperty      *property, 
							      GValue             *value);

void                    glade_property_get_va_list           (GladeProperty      *property,
							      va_list             vl);

void                    glade_property_get                   (GladeProperty      *property, 
							      ...);

void                    glade_property_add_object            (GladeProperty      *property,
							      GObject            *object);

void                    glade_property_remove_object         (GladeProperty      *property,
							      GObject            *object);

void                    glade_property_sync                  (GladeProperty      *property);

void                    glade_property_load                  (GladeProperty      *property);

GValue                 *glade_property_read                  (GladeProperty      *property,
							      GladePropertyClass *pclass,
							      GladeProject       *project,
							      gpointer            info,
							      gboolean            free_value);

gboolean                glade_property_write                 (GladeProperty      *property, 
							      GladeInterface     *interface, 
							      GArray             *props);

G_CONST_RETURN gchar   *glade_property_get_tooltip           (GladeProperty      *property);

void                    glade_property_set_sensitive         (GladeProperty      *property,
							      gboolean            sensitive,
							      const gchar        *reason);

gboolean                glade_property_get_sensitive         (GladeProperty      *property);


void                    glade_property_set_save_always       (GladeProperty      *property,
							      gboolean            setting);

gboolean                glade_property_get_save_always       (GladeProperty      *property);


void                    glade_property_set_enabled           (GladeProperty      *property,
							      gboolean            enabled);

gboolean                glade_property_get_enabled           (GladeProperty      *property);


void                    glade_property_i18n_set_comment      (GladeProperty      *property, 
							      const gchar        *str);

G_CONST_RETURN gchar   *glade_property_i18n_get_comment      (GladeProperty      *property);

void                    glade_property_i18n_set_translatable (GladeProperty      *property,
							      gboolean            translatable);

gboolean                glade_property_i18n_get_translatable (GladeProperty      *property);

void                    glade_property_i18n_set_has_context  (GladeProperty      *property,
							      gboolean            has_context);

gboolean                glade_property_i18n_get_has_context  (GladeProperty      *property);


void                    glade_property_push_superuser        (void);

void                    glade_property_pop_superuser         (void);

gboolean                glade_property_superuser             (void);

G_END_DECLS

#endif /* __GLADE_PROPERTY_H__ */
