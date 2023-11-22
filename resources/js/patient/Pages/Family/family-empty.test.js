/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import FamilyEmptyPage from "./FamilyEmpty.vue";

describe("FamilyEmptyPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(FamilyEmptyPage);
        expect(wrapper.exists()).toBe(true);
    });
});
